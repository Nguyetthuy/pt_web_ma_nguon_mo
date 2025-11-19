</div>
<footer class="bg-dark text-white text-center py-3 mt-5">
  &copy; <?= date('Y') ?> FlyHighEnglish
</footer>
<?php
// đảm bảo base path giống header
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/main.js"></script>
</body>
</html>