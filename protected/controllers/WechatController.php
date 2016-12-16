<?php
    class  WechatController extends Controller
    {   
        public function actionIndex1(){
          $this->render('index1');
        } 
        
        public function actionIndex(){
          $this->render('index');
        } 
        public function actionMoney(){
          $this->render('money');
        } 
         public function actionPoint(){
          $this->render('point');
        }
         public function actionTicket(){
          $this->render('ticket');
        }
    }


