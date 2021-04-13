<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">

 	<div class="row">

		<?php $widget->view($this, 'gatekeeper'); ?>

		<?php $widget->view($this, 'sip'); ?>

		<?php $widget->view($this, 'filevault'); ?>

	</div> <!-- /row -->

	<div class="row">

		<?php $widget->view($this, 'firmwarepw'); ?>

		<?php $widget->view($this, 'findmymac'); ?>

		<?php $widget->view($this, 'firewall_state'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->view($this, 'skel_state'); ?>

        <?php $widget->view($this, 'ssh_state'); ?>

        <?php $widget->view($this, 'root_user'); ?>

    </div> <!-- /row -->

    <div class="row">

        <?php $widget->view($this, 't2_secureboot'); ?>

        <?php $widget->view($this, 't2_externalboot'); ?>
        
        <?php $widget->view($this, 'activation_lock'); ?>

    </div> <!-- /row -->


</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
