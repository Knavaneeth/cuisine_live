<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking/Do/settings" class="uk-button"><i class="fa fa-cog"></i> <?php echo Yii::t("default","Settings")?></a>
</div>

<div class="spacer"></div>

<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$merchant_booking_alert=Yii::app()->functions->getOption("merchant_booking_alert",$merchant_id);
$tp1=Yii::app()->functions->getOption("merchant_booking_approved_tpl",$merchant_id);
$tp2=Yii::app()->functions->getOption("merchant_booking_denied_tpl",$merchant_id);
if ( empty($tp1)){
	$tp1=EmailTPL::bookingApproved();
}
if ( empty($tp2)){
	$tp2=EmailTPL::bookingDenied();
}
$subject=Yii::app()->functions->getOption("merchant_booking_subject",$merchant_id);
$sender=Yii::app()->functions->getOption("merchant_booking_sender",$merchant_id);
$merchant_booking_receiver=Yii::app()->functions->getOption("merchant_booking_receiver",$merchant_id);
$merchant_sms_booking_receiver = Yii::app()->functions->getOption("merchant_sms_booking_receiver",$merchant_id);
$merchant_booking_tpl=Yii::app()->functions->getOption("merchant_booking_tpl",$merchant_id);

if (empty($merchant_booking_tpl)){
	$merchant_booking_tpl=EmailTPL::bookingTPL();
}
$merchant_booking_receive_subject=Yii::app()->functions->getOption("merchant_booking_receive_subject",$merchant_id);

$days=Yii::app()->functions->getDays();

$max_booked=Yii::app()->functions->getOption("max_booked",$merchant_id);
if (!empty($max_booked)){
	$max_booked=json_decode($max_booked,true);
}

$fully_booked_msg=Yii::app()->functions->getOption("fully_booked_msg",$merchant_id);

$table_booking_settings =Yii::app()->functions->get_merchant_table_booking_settings($merchant_id);

?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','bookingAlertSettings')?>
<?php 
$base_url  = Yii::app()->getBaseUrl(true);
echo CHtml::hiddenField('base_url',$base_url);
?> 
<h3 style="text-transform:capitalize;"><?php echo t("maximum tables that can be booked per day")?></h3>
<div class="uk-form-row">

  <label class="uk-form-label"><?php echo Yii::t("default","Pick a date")?></label>
  <?php 
  echo CHtml::textField('merchant_picked_date','',array(
  	 'class'=>'uk-form-width-large j_date_table_booking',
		  'data-id'=>'date_booking',
		  'data-validation'=>"required" 
  ));
  ?> 
 
</div>

<div class="uk-form-row" id="table_booking_table" style="display:none;"> 
	<ul>
		    <li>
		       <div class="left" style="width:100px;"><?php  echo t($val)?></div>
		       <div class="left"> 

				   <table class="table">
				    <thead>
				      <tr>
				        <th>Available</th>
				        <th>Start Time</th>
				        <th>End Time</th>
				        <th>Seats Available</th>
				      <!--  <th>Seats Filled</th> -->
				      </tr>
				    </thead>
				    <tbody id="table_body">
				 
				    </tbody>
				  </table>


		        </div>
		       <div class="clear"></div>
		    </li> 

	</ul>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Table Booking")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_table_booking',
  Yii::app()->functions->getOption("merchant_table_booking",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Accept booking same day")?>?</label>
  <?php 
  echo CHtml::checkBox('accept_booking_sameday',
  getOption($merchant_id,'accept_booking_sameday')==2?true:false
  ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Fully booked message")?></label>
  <?php 
  echo CHtml::textArea('fully_booked_msg',$fully_booked_msg,array('class'=>'uk-form-width-large'))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Alert Notification")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_booking_alert',
  $merchant_booking_alert==1?true:false
  ,array('value'=>1,'class'=>"icheck"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email address")?></label>
  <?php 
  echo CHtml::textField('merchant_booking_receiver',$merchant_booking_receiver,array(
   'class'=>"uk-form-width-large",
   'data-validation'=>'email'
  ));
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","Email Address that will receive notification when there is new booking"
)?>.</p>

<!--
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mobile Number")?></label>
  <?php 
  echo CHtml::textField('merchant_sms_booking_receiver',$merchant_sms_booking_receiver,array(
   'class'=>"uk-form-width-large",
   'data-validation'=>'required'
  ));
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","Mobile that will receive notification when there is new booking"
)?>.</p>
-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Subject")?></label>
  <?php 
  echo CHtml::textField('merchant_booking_receive_subject',$merchant_booking_receive_subject,array(
   'class'=>"uk-form-width-large",
   'data-validation'=>'required'
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Template")?></label>
  <?php 
  echo CHtml::textArea('merchant_booking_tpl',$merchant_booking_tpl,array(
   'class'=>"uk-form-width-large big-textarea"
  ));
  ?>
</div>

<p><?php echo Yii::t("default","Available Tags")?></p>
<p class="uk-text-muted">{booking-information}</p>

<hr></hr>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email Sender")?></label>
  <?php 
  echo CHtml::textField('merchant_booking_sender',$sender,array(
   'class'=>"uk-form-width-large",
   'data-validation'=>'email'
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email Subject")?></label>
  <?php 
  echo CHtml::textField('merchant_booking_subject',$subject,array(
   'class'=>"uk-form-width-large",
   'data-validation'=>'required'
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Approved Email Template")?></label>
  <?php 
  echo CHtml::textArea('merchant_booking_approved_tpl',$tp1,array(
   'class'=>"uk-form-width-large big-textarea"
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Denied Email Template")?></label>
  <?php 
  echo CHtml::textArea('merchant_booking_denied_tpl',$tp2,array(
   'class'=>"uk-form-width-large big-textarea"
  ));
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

<p><?php echo Yii::t("default","Available Tags")?></p>
<p class="uk-text-muted">{customer-name}</p>
<p class="uk-text-muted">{booking-information}</p>

</form>