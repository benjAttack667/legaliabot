
    // Appliquer le mode sombre si stocké
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
      document.getElementById('toggle-mode').textContent = '☀️ Mode clair';
    }

    document.getElementById('toggle-mode').addEventListener('click', function () {
      document.body.classList.toggle('dark-mode');
      if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
        this.textContent = '☀️ Mode clair';
      } else {
        localStorage.setItem('darkMode', 'disabled');
        this.textContent = '🌙 Mode sombre';
      }
    });
