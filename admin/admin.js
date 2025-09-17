const API_BASE = 'http://localhost:8080';

// Require auth to access this page
if (typeof Auth !== 'undefined') {
  Auth.requireAuth();
}

// Elements
const tbody = document.getElementById('postsTBody');
const btnNew = document.getElementById('btnNew');
const modal = document.getElementById('modal');
const btnCloseModal = document.getElementById('btnCloseModal');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');
const form = document.getElementById('postForm');

const inputId = document.getElementById('postId');
const inputTitle = document.getElementById('title');
const inputSlug = document.getElementById('slug');
const inputContent = document.getElementById('content');
const inputTags = document.getElementById('tags');

let loading = false;
const btnLogout = document.getElementById('btnLogout');
if (btnLogout && typeof Auth !== 'undefined') {
  btnLogout.addEventListener('click', () => {
    Auth.clearToken();
    location.href = '/admin/login.html';
  });
}

function fmtDate(d) {
  if (!d) return '-';
  try {
    const dt = new Date(d);
    return dt.toLocaleString();
  } catch {
    return d;
  }
}

function openModal(mode = 'new', post = null) {
  modal.classList.remove('hidden');
  if (mode === 'edit' && post) {
    modalTitle.textContent = 'Edit Post';
    inputId.value = post.id;
    inputTitle.value = post.title;
    inputSlug.value = post.slug || '';
    inputContent.value = post.content || '';
    inputTags.value = (post.tags || []).join(', ');
  } else {
    modalTitle.textContent = 'New Post';
    inputId.value = '';
    form.reset();
  }
}

function closeModal() {
  modal.classList.add('hidden');
}

async function loadPosts() {
  setLoading(true);
  try {
    const res = typeof Auth !== 'undefined' ?
      await Auth.authFetch(`${API_BASE}/posts`) :
      await fetch(`${API_BASE}/posts`);
    if (!res.ok) throw new Error('Failed to load posts');
    const posts = await res.json();
    renderPosts(posts);
  } catch (e) {
    console.error(e);
    tbody.innerHTML = `<tr><td colspan="6" class="py-6 text-center text-red-600">${e.message || 'Error loading posts'}</td></tr>`;
  } finally {
    setLoading(false);
  }
}

function renderPosts(posts) {
  if (!Array.isArray(posts) || posts.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="py-6 text-center text-slate-500">No posts yet</td></tr>`;
    return;
  }

  tbody.innerHTML = '';
  for (const p of posts) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="py-2 pr-4 font-medium text-slate-800">${escapeHtml(p.title)}</td>
      <td class="py-2 pr-4 text-slate-600">${escapeHtml(p.slug)}</td>
      <td class="py-2 pr-4">
        ${(p.tags || []).map(tag => `<span class=\"inline-flex items-center px-2 py-0.5 mr-1 rounded-full text-xs bg-slate-100 text-slate-700\">${escapeHtml(tag)}</span>`).join('')}
      </td>
      <td class="py-2 pr-4 text-slate-600">${fmtDate(p.created_at)}</td>
      <td class="py-2 pr-4 text-slate-600">${fmtDate(p.updated_at)}</td>
      <td class="py-2 pr-4">
        <div class="flex items-center gap-2">
          <button class="px-3 py-1.5 text-sm rounded-lg border border-slate-300 hover:bg-slate-50" data-action="edit">Edit</button>
          <button class="px-3 py-1.5 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700" data-action="delete">Delete</button>
        </div>
      </td>
    `;

    tr.querySelector('[data-action="edit"]').addEventListener('click', () => openModal('edit', p));
    tr.querySelector('[data-action="delete"]').addEventListener('click', () => onDelete(p));

    tbody.appendChild(tr);
  }
}

function setLoading(isLoading) {
  loading = isLoading;
}

function parseTags(val) {
  if (!val) return [];
  return val
    .split(',')
    .map(s => s.trim())
    .filter(Boolean);
}

async function onDelete(post) {
  if (!confirm(`Delete post \"${post.title}\"?`)) return;
  try {
    const res = typeof Auth !== 'undefined' ?
      await Auth.authFetch(`${API_BASE}/posts/${encodeURIComponent(post.id)}`, { method: 'DELETE' }) :
      await fetch(`${API_BASE}/posts/${encodeURIComponent(post.id)}`, { method: 'DELETE' });
    if (!res.ok) throw new Error('Failed to delete');
    await loadPosts();
  } catch (e) {
    alert(e.message || 'Error deleting post');
  }
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = inputId.value;
  const payload = {
    title: inputTitle.value.trim(),
    slug: inputSlug.value.trim() || undefined,
    content: inputContent.value.trim(),
    tags: parseTags(inputTags.value)
  };

  try {
    let res;
    if (id) {
      // update
      const upd = {};
      if (payload.title) upd.title = payload.title;
      if (payload.slug) upd.slug = payload.slug;
      if (payload.content) upd.content = payload.content;
      if (payload.tags) upd.tags = payload.tags;
      res = typeof Auth !== 'undefined' ?
        await Auth.authFetch(`${API_BASE}/posts/${encodeURIComponent(id)}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(upd)
        }) :
        await fetch(`${API_BASE}/posts/${encodeURIComponent(id)}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(upd)
        });
    } else {
      // create
      const body = JSON.stringify({
        title: payload.title,
        slug: payload.slug,
        content: payload.content,
        tags: payload.tags
      });
      res = typeof Auth !== 'undefined' ?
        await Auth.authFetch(`${API_BASE}/posts`, {
          method: 'POST', headers: { 'Content-Type': 'application/json' }, body
        }) :
        await fetch(`${API_BASE}/posts`, {
          method: 'POST', headers: { 'Content-Type': 'application/json' }, body
        });
    }

    if (!res.ok) {
      const t = await res.text();
      throw new Error(t || 'Request failed');
    }
    closeModal();
    await loadPosts();
  } catch (e) {
    alert(e.message || 'Error saving post');
  }
});

btnNew.addEventListener('click', () => openModal('new'));
btnCloseModal.addEventListener('click', closeModal);
btnCancel.addEventListener('click', closeModal);

function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');
}

// Initial load
loadPosts();
