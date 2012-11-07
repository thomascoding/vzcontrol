<?php

/**
 * 
 * Copyright (c) 2012 Kevin Gravier <kevin@mrkmg.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */


function putLine($line){
    echo $line.PHP_EOL;
    return true;
}

function runSSH($server_name,$command){
    global $servers;
    $command = 'ssh root@'.$servers[$server_name]['host'].' "'.str_replace('"','\\"',$command).'"';
    //putLine($command);
    passthru($command);
    return true;
}

function list_servers($args,$all=false){
    global $servers;

    if(strlen($args)){
        $servers_wanted = explode(' ',$args);
        foreach($servers_wanted as $server_name){
            if(!isset($servers[$server_name])){
                putLine($server_name.' is not known');
                return false;
            }
        }
    }
    else{
        $servers_wanted = array_keys($servers);
    }

    foreach($servers_wanted as $server_name){
        putLine('Listing for '.$server_name);
        runSSH($server_name,'vzlist'.($all?' -a':''));
    }
    return true;
}

function list_all_servers($args){
    return list_servers($args,true);
}

function move_container($args,$live=false){
    global $servers;

    $args = explode(' ',$args);
    if(count($args) !== 3){
        putLine('Incorrect usage. Use mv[l] CTID SOURCE DEST');
        return false;
    }
    $ctid = $args[0];
    $source = $args[1];
    $dest = $args[2];

    if(!isset($servers[$source])){
        putLine($source.' not found.');
        return false;
    }
    elseif(!isset($servers[$dest])){
        putLine($dest.' not found.');
        return false;
    }

    putLine('Sending move command');
    runSSH($source,'vzmigrate'.($live?' --online ':' ').$servers[$dest]['host'].' '.$ctid);
    return true;
}

function move_container_online($args){
    return move_container($args,true);
}

function stop_container($args){
    global $servers;
    $args = explode(' ',$args);
    $host = $args[0];
    $ctid = $args[1];

    if(!isset($servers[$host])){
        putLine($host.' is not known');
        return false;
    }

    runSSH($host,'vzctl stop '.$ctid);
    return true;
}

function start_container($args){
    global $servers;
    $args = explode(' ',$args);
    $host = $args[0];
    $ctid = $args[1];

    if(!isset($servers[$host])){
        putLine($host.' is not known');
        return false;
    }

    runSSH($host,'vzctl start '.$ctid);

    return true;
}

function restart_container($args){
    global $servers;
    $args = explode(' ',$args);
    $host = $args[0];
    $ctid = $args[1];

    if(!isset($servers[$host])){
        putLine($host.' is not known');
        return false;
    }

    runSSH($host,'vzctl restart '.$ctid);

    return true;
}

function enter_container($args){
    global $servers;
    $args = explode(' ',$args);
    $host = $args[0];
    $ctid = $args[1];

    if(!isset($servers[$host])){
        putLine($host.' is not known');
        return false;
    }

    runSSH($host,'vzctl enter '.$ctid);

    return true;
}

function create_container($args){
    global $servers;
    global $reader;
    if(!isset($servers[$args])){
        putLine($args.' is not known');
        return false;
    }

    putLine('You will be prompted for a series of details.');
    $ctid = $reader->readLine('CTID? ');
    $ostemplate = $reader->readLine('OS Template? ');
    $ipaddr = $reader->readLine('IP Address? ');
    $hostname = $reader->readLine('Hostname? ');
    $nameserver = $reader->readLine('Nameserver? ');
    $pReader = new Password;
    $tries = 0;
    do{
        $rootPassword = $pReader->readLine('Root Password? ');
        $confirmPassword = $pReader->readLine('Confirm? ');
        $tries++;
    }while($tries <= 3 and !((!empty($rootPassword) and $rootPassword == $confirmPassword) or !putLine('Passwords did not match')));
    if($tries > 3){
        putLine('Quiting, password did not match');
        return false;
    }

    putLine('Creating container');
    runSSH($args,'vzctl create '.$ctid.' --ostemplate '.$ostemplate);
    runSSH($args,'vzctl set '.$ctid.' --ipadd '.$ipaddr.' --save');
    runSSH($args,'vzctl set '.$ctid.' --nameserver '.$nameserver.' --save');
    runSSH($args,'vzctl set '.$ctid.' --hostname '.$hostname.' --save');
    runSSH($args,'vzctl set '.$ctid.' --userpasswd root:'.$rootPassword.' --save');

    return true;
}

function destroy_container($args){
    global $servers;
    $args = explode(' ',$args);
    $host = $args[0];
    $ctid = $args[1];

    if(!isset($servers[$host])){
        putLine($host.' is not known');
        return false;
    }

    runSSH($host,'vzctl destroy '.$ctid);

    return true;
}

function list_templates($args){
    global $servers;

    if(strlen($args)){
        $servers_wanted = explode(' ',$args);
        foreach($servers_wanted as $server_name){
            if(!isset($servers[$server_name])){
                putLine($server_name.' is not known');
                return false;
            }
        }
    }
    else{
        $servers_wanted = array_keys($servers);
    }

    foreach($servers_wanted as $server_name){
        putLine('Listing Templates for '.$server_name);
        putLine('----------------------'.str_repeat('-', strlen($server_name)));
        runSSH($server_name,'ls /vz/template/cache | sed s/.tar.gz//');
        putLine('');
    }

    return true;
}

function list_online_templates($args){
    $url = 'download.openvz.org';
    $folder = 'template/precreated/';
    if(strlen($args)) $folder .= $args.'/';
    $conn = ftp_connect($url);
    $log = ftp_login($conn, 'anonymous','anonymous');
    $file_list = ftp_nlist($conn, $folder);
    $file_list = array_filter($file_list,function($o){ return preg_match('/\.tar\.gz$/', $o); });
    array_walk($file_list,function(&$o,$key,$folder){ $o = substr($o,strlen($folder)); $o = substr($o,0,strlen($o)-7); },$folder);
    putLine('All templates online');
    foreach($file_list as $file){
        putLine($file);
    }

    return true;
}

function download_template($args){
    global $servers;
    $url = 'download.openvz.org';
    $folder = 'template/precreated/';
    $args = explode(' ',$args);
    $host = $args[0];
    $template = $args[1];
    if(isset($args[2])) $folder .= $args[2].'/';

    if(!isset($servers[$host])){
        putLine($host.' is not known');
        return false;
    }

    putLine('Downloading requested template');
    runSSH($host,'wget http://'.$url.'/'.$folder.'/'.$template.'.tar.gz -O /vz/template/cache/'.$template.'.tar.gz --progress=bar:force');
    putLine('Template will show in list when complete');

    return true;
}

function raw($args){
    $args = explode(' ',$args);
    $host = $args[0];
    $command = $args[1];
    runSSH($host,$command);

    return true;
}


function help($args){
    global $function_mapping;
    if(strlen($args)){
        if(isset($function_mapping[$args])){
            $command = $args;
            $info = $function_mapping[$args];
            putLine($command.' '.$info['usage']);
            putLine("\t".$info['desc']);
            putLine('');
        }
        else{
            putLine('Help for command not found');
        }
    }
    else{
        foreach($function_mapping as $command=>$info){
            putLine($command.' '.$info['usage']);
            putLine("\t".$info['desc']);
            putLine('');
        }
    }
    return true;
}

function clear_screen($args){
    system('clear');
    return true;
}

function quit_program($args){
    global $reader;
    unset($reader);
    exit(1);
}

function showBanner(){
    putLine('######################################');
    putLine('#             VzControl              #');
    putLine('#           OpenVZ Manager           #');
    putLine('#                                    #');
    putLine('# Created By MrKMG <kevin@mrkmg.com> #');
    putLine('# Type `help` to start               #');
    putLine('######################################');
    putLine('');
}

?>