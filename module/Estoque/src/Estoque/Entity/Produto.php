<?php 

namespace Estoque\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity*/
class Produto
	{
		/**
		@ORM\Id
		@ORM\GeneratedValue(strategy="AUTO")
		@ORM\Column(type="integer")
		*/
		private $id;

		/**
		@ORM\Column(type="string")
		*/
		private $nome;

		/**
		@ORM\Column(type="decimal",scale=2)
		*/
		private $preco;

		/**
		@ORM\Column(type="string")
		*/
		private $descricao;

		public function __construct($nome, $preco, $descricao){
			$this->nome = $nome;
			$this->preco = $preco;
			$this->descricao = $descricao;
		}

		public function getId(){
			return $this->id;
		}

		public function getNome(){
			return $this->nome;
		}

		public function getPreco(){
			return $this->preco;
		}

		public function getDescricao(){
			return $this->descricao;
		}

		public function setNome($nome){
			$this->nome = $nome;
		}

		public function setPreco($preco){
			$this->preco = $preco;
		}

		public function setDescricao($descricao){
			$this->descricao = $descricao;
		}

	}

 ?>