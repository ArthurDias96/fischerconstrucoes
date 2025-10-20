
document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('contact-form');
  if (!form) return;
  form.addEventListener('submit', function(e) {
    var nome = document.getElementById('nome');
    var email = document.getElementById('email');
    var mensagem = document.getElementById('mensagem');
    if (!nome || !email || !mensagem) return;
    if (!nome.value.trim()) {
      e.preventDefault();
      alert('Preencha o campo Nome');
      nome.focus();
      return;
    }
    if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
      e.preventDefault();
      alert('Informe um e-mail válido');
      email.focus();
      return;
    }
    if (!mensagem.value.trim()) {
      e.preventDefault();
      alert('Preencha a mensagem');
      mensagem.focus();
      return;
    }
  });
});

