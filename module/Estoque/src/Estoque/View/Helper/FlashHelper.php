<?php 

	namespace Estoque\View\Helper;

	use Zend\View\Helper\AbstractHelper;
	class FlashHelper extends AbstractHelper
	{
		public function __invoke(){
			echo $this->view->flashMessenger()->render('default', array('alert', 'alert-info'));
			echo $this->view->flashMessenger()->render('error', array('alert', 'alert-danger'));
			echo $this->view->flashMessenger()->render('success', array('alert', 'alert-success'));
		}
	}

 ?>