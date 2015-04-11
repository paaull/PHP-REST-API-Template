<?php
/**
 * @desc Database class - used to initiate the database connection
 * @author Paul Doelle
 */

class DB
{
   private static $instance = null;
   static function get() {
       if(self::$instance == null) {
           try {
               self::$instance = new PDO('mysql:host=localhost;dbname=api_sample', 'root', 'admin');
               self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           } catch(PDOException $e) {
               throw new Exception("Unable to connect to the database - " . $e->getMessage(), 500);
           }
       }
       return self::$instance;
   }
   
   public function lastInsertId() {
		return self::$instance->lastInsertId();
   }
}