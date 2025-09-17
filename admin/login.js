const API_BASE = 'http://localhost:8080';

const form = document.getElementById('loginForm');
const inputUsername = document.getElementById('username');
const inputPassword = document.getElementById('password');
const errorBox = document.getElementById('error');

function showError(msg){
  errorBox.textContent = msg;
  errorBox.classList.remove('hidden');
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  errorBox.classList.add('hidden');
  try {
    const res = await fetch(`${API_BASE}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username: inputUsername.value.trim(), password: inputPassword.value })
    });
    if (!res.ok) throw new Error(await res.text() || 'Login gagal');
    const data = await res.json();
    Auth.setToken(data.token);
    const params = new URLSearchParams(location.search);
    const next = params.get('next') || '/admin/index.html';
    location.href = next;
  } catch (e) {
    showError(e.message || 'Login gagal');
  }
});
