// FoodNest JS

function validateRegister() {
  clearErrors();
  let valid = true;
  const name  = document.getElementById('full_name')?.value.trim();
  const email = document.getElementById('email')?.value.trim();
  const pass  = document.getElementById('password')?.value;
  const conf  = document.getElementById('confirm_password')?.value;
  const phone = document.getElementById('phone')?.value.trim();

  if (!name || name.length < 3) { showError('nameError','Name must be at least 3 characters.'); valid=false; }
  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('emailError','Enter a valid email.'); valid=false; }
  if (!pass || pass.length < 6) { showError('passwordError','Password must be at least 6 characters.'); valid=false; }
  if (pass !== conf) { showError('confirmError','Passwords do not match.'); valid=false; }
  if (phone && !/^[0-9]{11}$/.test(phone)) { showError('phoneError','Phone must be 11 digits.'); valid=false; }
  return valid;
}

function validateLogin() {
  clearErrors();
  let valid = true;
  if (!document.getElementById('email')?.value.trim()) { showError('emailError','Email is required.'); valid=false; }
  if (!document.getElementById('password')?.value) { showError('passwordError','Password is required.'); valid=false; }
  return valid;
}

function changeQty(delta) {
  const input = document.getElementById('quantity');
  if (!input) return;
  let val = parseInt(input.value) + delta;
  if (val < 1) val = 1;
  if (val > 20) val = 20;
  input.value = val;
}

function confirmDelete(msg) { return confirm(msg || 'Are you sure?'); }

function showError(id, msg) { const el=document.getElementById(id); if(el) el.textContent=msg; }
function clearErrors() { document.querySelectorAll('.error').forEach(el=>el.textContent=''); }

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.alert').forEach(a => {
    setTimeout(() => { a.style.transition='opacity 0.5s'; a.style.opacity='0'; setTimeout(()=>a.remove(),500); }, 4000);
  });
});
