</div><!-- end .main -->
</body>
<script>
function confirmDelete(msg) { return confirm(msg || 'Are you sure?'); }
document.querySelectorAll('.alert').forEach(a => {
  setTimeout(() => { a.style.transition='opacity 0.5s'; a.style.opacity='0'; setTimeout(()=>a.remove(),500); }, 4000);
});
</script>
</html>
