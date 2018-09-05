  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <a href="http://isocial.com.bd/">iSocial</a>.</strong> All rights
    reserved.
  </footer>
</div>
<!-- ./wrapper -->


<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo str_replace('_', ' ', ucfirst($controller)); ?></h4>
      </div>
      <div class="modal-body">
        <div id="modal_default_body_warning" style="color: red;font-weight: bold;"></div>
        <div id="modal_default_body">
          <p>Loading ...</p>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>

<script>
	$(function () {
		$('#example1').DataTable();

    $(document).on('click', '.add', function(){
      $.get("<?php echo base_url().$controller; ?>/add_new_form", function(data, status){
        $('#modal_default_body').html(data);
      });
    });

    $(document).on('click', '.edit', function(){
      var id = $(this).data('id');
      $.get("<?php echo base_url().$controller; ?>/edit_form/"+id, function(data, status){
        $('#modal_default_body').html(data);
      });
    });

    $(document).on('click', '.delete', function(){
      var id = $(this).data('id');
      var confirm = window.confirm("Is it OK to delete?");
      if(confirm)
      {
        $.get("<?php echo base_url().$controller; ?>/delete_data/"+id, function(data, status){
          location.reload();
        });
      }
    });

    //for ajax forms
    $(document).on('submit', '.ajax_form', function(e) {
      var form = $(this);
      var url = form.attr('action');

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
          // show response from the php script.
          if(data.length == 0)
            location.reload();
          
          $("#modal_default_body_warning").html(data);
        }
      });

      e.preventDefault(); // avoid to execute the actual submit of the form.
    });
	});
</script>
</body>
</html>
