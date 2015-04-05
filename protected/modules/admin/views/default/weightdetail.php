                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'amount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderProduct, 'amount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('amount')));?>
                                                                                <?php echo $form->error($orderProduct, 'amount' )?>
                                                                        </div>
                                                                </div>
							<script>
                                                         
							</script>