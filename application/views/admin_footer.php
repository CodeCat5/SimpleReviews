<div class="clearfix"></div>

<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>

<script src="../assets/jquery-3.2.1.min.js"></script>
<script src="../assets/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<?= (isset($footer_extra) ? $footer_extra : ''); ?>

<script src="../js/admin.js"></script>

</body>
</html>