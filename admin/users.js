const API_BASE = 'http://localhost:8080';

// Require auth to access this page
if (typeof Auth !== 'undefined') {
  Auth.requireAuth();
}

const tbody = document.getElementById('usersTBody');
const btnNew = document.getElementById('btnNewUser');
const modal = document.getElementById('userModal');
const btnClose = document.getElementById('btnCloseUserModal');
const btnCancel = document.getElementById('btnCancelUser');
const form = document.getElementById('userForm');
const modalTitle = document.getElementById('userModalTitle');

const inputId = document.getElementById('userId');
const inputUsername = document.getElementById('username');
const inputPassword = document.getElementById('password');
const inputRoles = document.getElementById('roles');

function openModal(mode = 'new', user = null) {
  modal.classList.remove('hidden');
  if (mode === 'edit' && user) {
    modalTitle.textContent = 'Edit User';
    inputId.value = user.id;
    inputUsername.value = user.username;
    inputPassword.value = '';
    inputRoles.value = (user.roles || []).join(', ');
  } else {
    modalTitle.textContent = 'New User';
    inputId.value = '';
    form.reset();
  }
}

function closeModal() {
  modal.classList.add('hidden');
}

function parseRoles(val) { return (val || '').split(',').map(s => s.trim()).filter(Boolean); }

function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');
}

function fmtDate(d) { try { return d ? new Date(d).toLocaleString() : '-'; } catch { return d || '-'; } }

const btnLogout = document.getElementById('btnLogout');
if (btnLogout && typeof Auth !== 'undefined') {
  btnLogout.addEventListener('click', () => {
    Auth.clearToken();
    location.href = '/admin/login.html';
  });
}

async function loadUsers() {
  try {
    const res = typeof Auth !== 'undefined' ?
      await Auth.authFetch(`${API_BASE}/users`) :
      await fetch(`${API_BASE}/users`);
    if (!res.ok) throw new Error(await res.text() || 'Failed to load users');
    const users = await res.json();
    render(users);
  } catch (e) {
    tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-red-600">${e.message || 'Error'}</td></tr>`;
  }
}

function render(users) {
  if (!Array.isArray(users) || users.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-500">No users</td></tr>`;
    return;
  }
  tbody.innerHTML = '';
  for (const u of users) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="py-2 pr-4 font-medium text-slate-800">${escapeHtml(u.username)}</td>
      <td class="py-2 pr-4">${(u.roles || []).map(r => `<span class=\"inline-flex items-center px-2 py-0.5 mr-1 rounded-full text-xs bg-slate-100 text-slate-700\">${escapeHtml(r)}</span>`).join('')}</td>
      <td class="py-2 pr-4 text-slate-600">${fmtDate(u.created_at)}</td>
      <td class="py-2 pr-4 text-slate-600">${fmtDate(u.updated_at)}</td>
      <td class="py-2 pr-4">
        <div class="flex items-center gap-2">
          <button class="px-3 py-1.5 text-sm rounded-lg border border-slate-300 hover:bg-slate-50" data-action="edit">Edit</button>
          <button class="px-3 py-1.5 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700" data-action="delete">Delete</button>
        </div>
      </td>
    `;
    tr.querySelector('[data-action="edit"]').addEventListener('click', () => openModal('edit', u));
    tr.querySelector('[data-action="delete"]').addEventListener('click', () => onDelete(u));
    tbody.appendChild(tr);
  }
}

async function onDelete(user) {
  if (!confirm(`Delete user \"${user.username}\"?`)) return;
  try {
    const res = typeof Auth !== 'undefined' ?
      await Auth.authFetch(`${API_BASE}/users/${encodeURIComponent(user.id)}`, { method: 'DELETE' }) :
      await fetch(`${API_BASE}/users/${encodeURIComponent(user.id)}`, { method: 'DELETE' });
    if (!res.ok) throw new Error(await res.text() || 'Failed to delete');
    await loadUsers();
  } catch (e) { alert(e.message || 'Error deleting'); }
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = inputId.value;
  const username = inputUsername.value.trim();
  const password = inputPassword.value;
  const roles = parseRoles(inputRoles.value);

  try {
    let res;
    if (id) {
      const upd = { username, roles };
      if (password) upd.password = password;
      res = typeof Auth !== 'undefined' ?
        await Auth.authFetch(`${API_BASE}/users/${encodeURIComponent(id)}`, {
          method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(upd)
        }) :
        await fetch(`${API_BASE}/users/${encodeURIComponent(id)}`, {
          method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(upd)
        });
    } else {
      const body = JSON.stringify({ username, password, roles });
      res = typeof Auth !== 'undefined' ?
        await Auth.authFetch(`${API_BASE}/users`, {
          method: 'POST', headers: { 'Content-Type': 'application/json' }, body
        }) :
        await fetch(`${API_BASE}/users`, {
          method: 'POST', headers: { 'Content-Type': 'application/json' }, body
        });
    }
    if (!res.ok) throw new Error(await res.text() || 'Request failed');
    closeModal();
    await loadUsers();
  } catch (e) { alert(e.message || 'Error saving'); }
});

btnNew.addEventListener('click', () => openModal('new'));
btnClose.addEventListener('click', closeModal);
btnCancel.addEventListener('click', closeModal);

loadUsers();
