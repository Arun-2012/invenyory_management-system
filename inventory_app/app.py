from flask import Flask, render_template, request, jsonify
import sqlite3
from datetime import datetime

app = Flask(__name__)

# Initialize database
def init_db():
    conn = sqlite3.connect('inventory.db')
    c = conn.cursor()
    c.execute('''CREATE TABLE IF NOT EXISTS inventory
                 (id INTEGER PRIMARY KEY AUTOINCREMENT,
                 name TEXT NOT NULL,
                 quantity INTEGER NOT NULL,
                 price REAL NOT NULL,
                 date_added TEXT NOT NULL)''')
    conn.commit()
    conn.close()

# Database connection helper
def get_db_connection():
    conn = sqlite3.connect('inventory.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/')
def index():
    return render_template('index.html')

# API Endpoints
@app.route('/api/items', methods=['GET'])
def get_items():
    search = request.args.get('search', '')
    conn = get_db_connection()
    
    if search:
        items = conn.execute('SELECT * FROM inventory WHERE name LIKE ?', 
                           (f'%{search}%',)).fetchall()
    else:
        items = conn.execute('SELECT * FROM inventory').fetchall()
    
    conn.close()
    return jsonify([dict(item) for item in items])

@app.route('/api/items', methods=['POST'])
def add_item():
    data = request.get_json()
    name = data['name']
    quantity = data['quantity']
    price = data['price']
    date_added = datetime.now().isoformat()
    
    conn = get_db_connection()
    conn.execute('INSERT INTO inventory (name, quantity, price, date_added) VALUES (?, ?, ?, ?)',
                (name, quantity, price, date_added))
    conn.commit()
    new_id = conn.execute('SELECT last_insert_rowid()').fetchone()[0]
    conn.close()
    
    return jsonify({'id': new_id, 'name': name, 'quantity': quantity, 'price': price, 'date_added': date_added})

@app.route('/api/items/<int:item_id>', methods=['PUT'])
def update_item(item_id):
    data = request.get_json()
    name = data['name']
    quantity = data['quantity']
    price = data['price']
    
    conn = get_db_connection()
    conn.execute('UPDATE inventory SET name = ?, quantity = ?, price = ? WHERE id = ?',
                (name, quantity, price, item_id))
    conn.commit()
    conn.close()
    
    return jsonify({'success': True})

@app.route('/api/items/<int:item_id>', methods=['DELETE'])
def delete_item(item_id):
    conn = get_db_connection()
    conn.execute('DELETE FROM inventory WHERE id = ?', (item_id,))
    conn.commit()
    conn.close()
    
    return jsonify({'success': True})

if __name__ == '__main__':
    init_db()
    app.run(debug=True)