<?php
namespace App\Http\Controllers;

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('phpseclib\\', __DIR__ . '/path/to/phpseclib2.0');
$loader->register();

use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class TestsController extends Controller
{

		public function TestSSH(){

			$ssh = new SSH2('91.150.80.61', 2244);
			if (!$ssh->login('root', 'password')) {
			    exit('Login Failed');
			}

			echo "great";
	    }
}


 ?>