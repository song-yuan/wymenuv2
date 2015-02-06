<?php
class Wsv2Controller extends CController
{
	public function actions()
        {
            return array(
                'index'=>array(
                    'class'=>'CWebServiceAction',
                ),
            );
        }
    
        /**
         * @param string the symbol of the stock
         * @return float the stock price
         * @soap
         */
        public function getPrice($symbol)
        {
            $prices=array('IBM'=>100, 'GOOGLE'=>350);
            return isset($prices[$symbol])?$prices[$symbol]:0;
            //...return stock price for $symbol
        }
        
        /**
         * @param string the dpid for table number length 10
         * @param string the cmd for table
         * @param string the data detail
         * @return int success 1 or fail 0
         * @soap
         */
        public function baseDataDown($dpid,$cmd,$strdata)
        {
            $bd=new BaseDataMsg($dpid);
            $res=0;
            switch ($cmd)
            {
                case 'CPLB':
                {
                    $bd->saveCmd($cmd);
                    $res= $bd->CPLB($strdata);
                    break;
                }
                case 'XZCP':
                {
                    $bd->saveCmd($cmd);
                    $res= $bd->XZCP($strdata);
                    break;
                }
                case 'XZTC':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->XZTC($strdata);
                    break;
                }
                case 'TCNR':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->TCNR($strdata);
                    break;
                }
                case 'XZQY':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->XZQY($strdata);
                    break;
                }
                case 'KWZF':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->KWZF($strdata);
                    break;
                }
                case 'CZBX':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->CZBX($strdata);
                    break;
                }
                case 'FKXX':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->FKXX($strdata);
                    break;
                }
                case 'DPKW':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->DPKW($strdata);
                    break;
                }
                case 'GQLB':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->GQLB($strdata);
                    break;
                }
                case 'SJLB':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->SJLB($strdata);
                    break;
                }
                case 'YHHD':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->YHHD($strdata);
                    break;
                }
                case 'TJLB':
                {
                    $bd->saveCmd($cmd);
                    $res=  $bd->TJLB($strdata);
                    break;
                }
                default :
                    return 0;//fail
            }
            
            if($res===1)
            {
                $bd->updateResult($cmd,'1');
                return 1;
            }
            else
            {
                $bd->updateResult($cmd,'2');
                return 0;
            }
            return 0;
        }
        
        /**
         * @param string the dpid for Sn number length 10
         * @param string the cmd for Sn
         * @param string the data of Sn
         * @return int success or fail
         * @soap
         */
        public function dealSn($dpid,$cmd,$strdata)
        {
            $sm=new SMsg($dpid);
            $res=0;
            switch ($cmd)
            {
                case 'KT':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->KT($strdata);
                    break;
                }
                case 'HT':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->HT($strdata);
                    break;
                }
                case 'BT':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->BT($strdata);
                    break;
                }
                case 'CT':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->CT($strdata);
                    break;
                }
                case 'TC':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->TC($strdata);
                    break;
                }
                case 'GT':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->GT($strdata);
                    break;
                }
                case 'ZLQR':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->ZLQR($strdata);
                    break;
                }
                case 'ZC':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->ZC($strdata);
                    break;
                }
                case 'JDWC':
                {
                    $sm->saveCmdData($cmd, $strdata);
                    $res=$sm->JDWC($strdata);
                    break;
                }
                default :
                    return 1;//fail
            }
            
            if($res===1)
            {
                $sm->updateResult($cmd,'1');
                return 1;
            }
            else
            {
                $sm->updateResult($cmd,'2');
                return 0;
            }
        }
        
        /**
         * @param string the last cmd_lid sended to interface
         * @return string Wn cmd and data
         * @soap
         */
        public function getNewWn($dpid)
        {
            $wm=new WMsg($dpid);
            //if no more wn message return "no"
            return $wm->getMsg();
            //...return stock price for $symbol
        }
        
        /**
         * @param string the current dpid for Wn
         * @param string the current cmd for Wn
         * @param string the current lid for Wn
         * @param int the deal result of the currentWn success 1 or fail 0
         * @return int success 1 or fail 0
         * @soap
         */
        public function setWnResult($dpid,$cmd,$currentlid,$result)
        {
            $wm=new WMsg($dpid);
            $res=0;
            if($result===1)
            {
                $res=1;
            }
            else
            {
                $res=2;
            }
            return $wm->updateResult($cmd, $currentlid, $res);
            //...return stock price for $symbol
        }
}