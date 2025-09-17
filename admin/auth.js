// Simple auth utilities for Admin Dashboard
(function(){
  const KEY = 'auth_token';

  function getToken(){
    return localStorage.getItem(KEY) || '';
  }
  function setToken(t){
    if (t) localStorage.setItem(KEY, t);
  }
  function clearToken(){
    localStorage.removeItem(KEY);
  }

  async function authFetch(url, options = {}){
    const token = getToken();
    const headers = new Headers(options.headers || {});
    if (token) headers.set('Authorization', `Bearer ${token}`);
    const res = await fetch(url, { ...options, headers });
    if (res.status === 401) {
      // token invalid/expired
      clearToken();
      // redirect to login page preserving next
      const next = encodeURIComponent(location.pathname + location.search);
      location.href = `/admin/login.html?next=${next}`;
      throw new Error('Unauthorized');
    }
    return res;
  }

  function requireAuth(){
    const token = getToken();
    if (!token) {
      const next = encodeURIComponent(location.pathname + location.search);
      location.href = `/admin/login.html?next=${next}`;
      return false;
    }
    return true;
  }

  // expose
  window.Auth = { getToken, setToken, clearToken, authFetch, requireAuth };
})();
