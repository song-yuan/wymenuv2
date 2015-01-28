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
         * @param string the cmd for table
         * @param string the data detail
         * @return int success or fail
         * @soap
         */
        public function baseDataDown($cmd,$strdata)
        {
            echo("The table is:".$cmd."\n");
            echo("The detail data is:".$strdata."\n");
            return 1;
            //...return stock price for $symbol
        }
        
        /**
         * @param string the cmd for Sn
         * @param string the data of Sn
         * @return int success or fail
         * @soap
         */
        public function dealSn($cmd,$strdata)
        {
            echo("The cmd is:".$cmd."\n");
            echo("The data is:".$strdata."\n");
            return 1;
            //...return stock price for $symbol
        }
        
        /**
         * @param string the last code sended to interface
         * @return string Wn cmd and data
         * @soap
         */
        public function getNewWn($lastcode)
        {
            echo("The lastcode is:".$lastcode."\n");
            //if no more wn message return "no"
            return "flajfdlajfdlsajfasdfjkdsalfjkf";
            //...return stock price for $symbol
        }
        
        /**
         * @param string the currentcode for Wn
         * @param int the deal result of the currentWn success 1 or fail 0
         * @return int success 1 or fail 0
         * @soap
         */
        public function setWnResult($currentcode,$result)
        {
            echo("The currentcode is:".$currentcode."\n");
            echo("The result is:".$result."\n");
            return 1;
            //...return stock price for $symbol
        }
}