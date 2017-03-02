<?php
	
	namespace Estoque\Controller;

	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\View\Model\ViewModel;
	use Estoque\Entity\Produto;
	use Zend\Mail\Message;
	use Zend\Mail\Transport\Smtp as SmtpTransport;
	use Zend\Mail\Transport\SmtpOptions;
	use Zend\Mime\Message as MimeMessage;
	use Zend\Mime\Part as MimePart;
	

	class IndexController extends AbstractActionController {

		public function IndexAction() {	

			$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
			$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');

			$produtos = $repositorio->findAll();
 
			$view_params = array(
				'produtos' => $produtos
			);
			return new ViewModel($view_params);
		}

		public function cadastrarAction(){

			if ($this->request->isPost()) {
				$nome = $this->request->getPost('nome');
				$preco = $this->request->getPost('preco');
				$descricao = $this->request->getPost('descricao');				

				$produto = new Produto($nome,$preco,$descricao);

				$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

				$entityManager->persist($produto);
				$entityManager->flush();

				$this->flashMessenger()->addSuccessMessage('Produto cadastrado com sucesso!');

				return $this->redirect()->toUrl('/Index/cadastrar');

			}
			return new ViewModel();
		}

		public function removerAction(){

			$id = $this->params()->fromRoute('id');
	        if(is_null($id)) {
            	$id = $this->params()->fromPost('id');
       		}

			if ($this->request->isPost()) {
				
				$id = $this->request->getPost('id');

				$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
				$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');

				$produto = $repositorio->find($id);

				$entityManager->remove($produto);
				$entityManager->flush();

				$this->flashMessenger()->addSuccessMessage('Produto removido com sucesso!');

				return $this->redirect()->toUrl('/Index/Index');
			}

			return new ViewModel(['id' => $id]);
		}

		public function editarAction(){

			$id = $this->params()->fromRoute('id');

	        if(is_null($id)) {
            	$id = $this->request->getPost('id');
       		}

			$entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
			$repositorio = $entityManager->getRepository('Estoque\Entity\Produto');

			$produto = $repositorio->find($id);

			if ($this->request->isPost()) {

				$produto->setNome($this->request->getPost('nome'));
				$produto->setPreco($this->request->getPost('preco'));
				$produto->setDescricao($this->request->getPost('descricao'));		

				$entityManager->persist($produto);
				$entityManager->flush();

				$this->flashMessenger()->addSuccessMessage('Produto alterado com sucesso!');

				return $this->redirect()->toUrl('/Index');
			}

			return new ViewModel(['produto' => $produto]);
		}

		public function contatoAction(){

			if($this->request->isPost()) {
        		$nome     = $this->request->getPost('nome');
        		$email    = $this->request->getPost('email');
        		$mensagem = $this->request->getPost('msg');

				$msgHtml = "
    				<b>Nome:</b> {$nome},<br>
    				<b>Email:</b> {$email},<br>
    				<b>Mensagem=:</b> {$msg},<br>
				";

				$htmlPart = new MimePart($msgHtml);
				$htmlPart->type = 'type/html';

				$htmlMsg = new MimeMessage();
				$htmlMsg->addPart($htmlPart);

				$email = new Message();
				$email->addTo('franjineassis@gmail.com');
				$email->setSubject('Contato feito pelo site');
				$email->addFrom('franjineassis@gmail.com');

				$email->setBody($htmlMsg);

				$config = array(
					'host' => 'smtp.gmail.com',
					'connection_class' => 'login',
					'connection_config' => array(
						'ssl' => 'tls',
						'username' => 'franjineassis@gmail.com',
						'password' => 'alargador',
					),
					'port' => 587
				);
				$transport = new SmtpTransport();
				$options = new SmtpOptions($config);
				$transport->setOptions($options);

				$transport->send($email);

				$this->flashMessenger()->addMessage('E-mail enviado com sucesso :D');

				return $this->redirect()->toUrl('/Index');
   			 }

			return new ViewModel();
		}
	}
?>