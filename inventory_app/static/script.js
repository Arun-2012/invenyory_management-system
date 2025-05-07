// DOM Elements
const itemNameInput = document.getElementById('item-name');
const itemQuantityInput = document.getElementById('item-quantity');
const itemPriceInput = document.getElementById('item-price');
const addBtn = document.getElementById('add-btn');
const searchInput = document.getElementById('search-item');
const inventoryList = document.getElementById('inventory-list');

let editingId = null;

// Fetch all items
async function fetchItems(search = '') {
    const response = await fetch(`/api/items?search=${encodeURIComponent(search)}`);
    return await response.json();
}

// Add or Update item
async function saveItem(item) {
    const url = editingId ? `/api/items/${editingId}` : '/api/items';
    const method = editingId ? 'PUT' : 'POST';
    
    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(item)
    });
    
    return await response.json();
}

// Delete item
async function deleteItem(id) {
    const response = await fetch(`/api/items/${id}`, {
        method: 'DELETE'
    });
    return await response.json();
}

// Render inventory list
async function renderInventory(search = '') {
    const items = await fetchItems(search);
    inventoryList.innerHTML = items.map(item => `
        <tr>
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>${item.quantity}</td>
            <td>$${parseFloat(item.price).toFixed(2)}</td>
            <td>$${(item.quantity * item.price).toFixed(2)}</td>
            <td>
                <button class="action-btn edit-btn" onclick="editItem(${item.id})">Edit</button>
                <button class="action-btn delete-btn" onclick="deleteItemHandler(${item.id})">Delete</button>
            </td>
        </tr>
    `).join('');
}

// Edit item
async function editItem(id) {
    const items = await fetchItems();
    const item = items.find(i => i.id === id);
    
    if (item) {
        itemNameInput.value = item.name;
        itemQuantityInput.value = item.quantity;
        itemPriceInput.value = item.price;
        editingId = id;
        addBtn.textContent = 'Update Item';
    }
}

// Delete item handler
async function deleteItemHandler(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        await deleteItem(id);
        await renderInventory(searchInput.value);
    }
}

// Clear form inputs
function clearInputs() {
    itemNameInput.value = '';
    itemQuantityInput.value = '';
    itemPriceInput.value = '';
    editingId = null;
    addBtn.textContent = 'Add Item';
}

// Event Listeners
addBtn.addEventListener('click', async () => {
    const name = itemNameInput.value.trim();
    const quantity = parseInt(itemQuantityInput.value);
    const price = parseFloat(itemPriceInput.value);

    if (name && !isNaN(quantity) && !isNaN(price)) {
        await saveItem({ name, quantity, price });
        await renderInventory(searchInput.value);
        clearInputs();
    } else {
        alert('Please fill all fields correctly!');
    }
});

searchInput.addEventListener('input', async () => {
    await renderInventory(searchInput.value);
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    renderInventory();
});