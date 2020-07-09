<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Server;
use App\Box;
use App\Ip;
use Illuminate\Support\Facades\Crypt;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;


$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4('phpseclib\\', __DIR__ . '/path/to/phpseclib2.0');
$loader->register();




class ServerController extends Controller
{
    public function index(){
        $user = auth()->user();
        $servers = Server::where('clientid', $user->id)
               ->orderBy('id', 'desc')
               ->get();

               
        if ($user) {
            $reposne['result'] = true;
            $reposne['servers'] = $servers; 
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $reposne;
    }

    public function getServer($id){
      $user = auth()->user();
      return Server::find($id)
              ->where('id', $id)
              ->where('clientid', $user->id)
              ->get();
    }

    public function getBox($boxId){
      return Box::where('id', $boxId)->get();
    }

    public function getIp($ipId){
      return Ip::where('id', $ipId)->get();
    }

    public function show($id)
    {
        $user = auth()->user();
        $server = getServer($id);

        $ip = $this->getIp($server[0]->ipid);
        $IpPort = $ip[0]->ip . ":" . $server[0]->port;
        $gamequery = $this->gameQ($IpPort);

        if ($user) {
            $reposne['ip'] = $IpPort;
            $reposne['server'] = $server;
            $reposne['gamequery'] = $gamequery;
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $reposne;
    }

    public function gameQ($ipPort){

      $GameQ = new \GameQ\GameQ();
      $GameQ->addServer([
            'id' => 'server',
            'type' => 'cs16',
            'host' => $ipPort,
        ]);

        return $GameQ->process();
    }

     public function start($id){
      $server = $this->getServer($id);
      $box = $this->getBox($server[0]->boxid);
      $ip = $this->getIp($server[0]->ipid);
      $password = Crypt::decryptString($box[0]->password);

      $startline = htmlspecialchars_decode($server[0]->startline);
      $startline = str_replace("{ip}",$ip[0]->ip,$startline);
      $startline = str_replace("{port}",$server[0]->port,$startline);
      $startline = str_replace("{slots}",$server[0]->slots,$startline);
      $startline = str_replace("{homedir}",$server[0]->homedir,$startline);
      $startline = str_replace("{cfg1}",$server[0]->cfg1,$startline);
      $startline = str_replace("{cfg2}",$server[0]->cfg2,$startline);
      $startline = str_replace("{cfg3}",$server[0]->cfg3,$startline);
      $startline = str_replace("{cfg4}",$server[0]->cfg4,$startline);
      $startline = str_replace("{cfg5}",$server[0]->cfg5,$startline);
      $startline = str_replace("{cfg6}",$server[0]->cfg6,$startline);
      $startline = str_replace("{cfg7}",$server[0]->cfg7,$startline);
      $startline = str_replace("{cfg8}",$server[0]->cfg8,$startline);
      $startline = str_replace("{serverid}",$server[0]->id,$startline);

      $ssh = new SSH2($box[0]->ip, $box[0]->sshport);
      if (!$ssh->login($box[0]->login, $password)) {
          exit('Login Failed');
      }
        $ssh->setTimeout(2);
        $ssh->read();
        $ssh->write("cd ".$server[0]->homedir." && ");
        $ssh->write("screen -S ".$server[0]->user." ".$startline."\n");
        echo $ssh->read();
    }

    public function stop($id){
      $user = auth()->user();
      $server = Server::find($id)
            ->where('id', $id)
            ->where('clientid', $user->id)
            ->get();

      $boxId = $server[0]->boxid;
      $box = Box::where('id', $boxId)->get();
      $password = Crypt::decryptString($box[0]->password);

      $ssh = new SSH2($box[0]->ip, $box[0]->sshport);
      if (!$ssh->login($box[0]->login, $password)) {
          exit('Login Failed');
      }
      $ssh->write("screen -XS ".$server[0]->user." quit && ");
      $ssh->setTimeout(1);
      $ssh->write("screen -list\n");
      echo $ssh->read();
        
    }

    public function webftp($id){
      $user = auth()->user();
      $server = Server::find($id)
            ->where('id', $id)
            ->where('clientid', $user->id)
            ->get();

      $ip = $this->getIp($server[0]->ipid);


      $ftp_server = $ip[0]->ip;
      $ftp_user = $server[0]->user;
      $ftp_pass = $server[0]->password;

      $conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 



      $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

      $mode = ftp_pasv($conn_id, TRUE);

        if ((!$conn_id) || (!$login_result) || (!$mode)) {
           die("FTP connection has failed !");
        }

        $file_list = ftp_rawlist($conn_id, "");
        
        return $file_list;
    
        ftp_close($conn_id);  
    }
}
 