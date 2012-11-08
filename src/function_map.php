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

$function_mapping = array(
    'ls'=>array(
        'func'=>'list_servers',
        'usage'=>'[server1] [server2] [etc]',
        'desc'=>'List running containers on servers'
    ),
    'lsa'=>array(
        'func'=>'list_all_servers',
        'usage'=>'[server1] [server2] [etc]',
        'desc'=>'List all containers on servers'
    ),
    'lst'=>array(
        'func'=>'list_templates',
        'usage'=>'[server1] [server2] [etc]',
        'desc'=>'List templates on server'
    ),
    'lsot'=>array(
        'func'=>'list_online_templates',
        'usage'=>'[section]',
        'desc'=>'List avaliable template for download'
    ),
    'install'=>array(
        'func'=>'download_template',
        'usage'=>'HOST TEMPLATE [section]',
        'desc'=>'Install TEMPLATE from [section] on HOST'
    ),
    'mv'=>array(
        'func'=>'move_container',
        'usage'=>'CTID CURRENTHOST DESTHOST',
        'desc'=>'Perform an offline migration of containter CTID on CURRENTHOST to DESTHOST'
    ),
    'mvo'=>array(
        'func'=>'move_container_online',
        'usage'=>'CTID CURRENTHOST DESTHOST',
        'desc'=>'Perform an online migration of container CTID on CURRENTHOST to DESTHOST'
    ),
    'start'=>array(
        'func'=>'start_container',
        'usage'=>'HOST CTID',
        'desc'=>'Start container CTID on HOST'
    ),
    'stop'=>array(
        'func'=>'stop_container',
        'usage'=>'HOST CTID',
        'desc'=>'Stop containter CTID on HOST'
    ),
    'restart'=>array(
        'func'=>'restart_container',
        'usage'=>'HOST CTID',
        'desc'=>'Restart containter CTID on HOST'
    ),
    'enter'=>array(
        'func'=>'enter_container',
        'usage'=>'HOST CTID',
        'desc'=>'Enter containter CTID on HOST'
    ),
    'create'=>array(
        'func'=>'create_container',
        'usage'=>'HOST',
        'desc'=>'Create a new container on HOST'
    ),
    'rm'=>array(
        'func'=>'destroy_container',
        'usage'=>'HOST CTID',
        'desc'=>'Destroy containter CTID on HOST'
    ),
    'reboot'=>array(
        'func'=>'reboot_host',
        'usage'=>'server1 [server2] [etc]',
        'desc'=>'Reboot OpenVZ Host(s)'
    ),
    'shutdown'=>array(
        'func'=>'shutdown_host',
        'usage'=>'server1 [server2] [etc]',
        'desc'=>'Shutdown OpenVZ Host(s)'
    ),
    'uptime'=>array(
        'func'=>'uptime',
        'usage'=>'[server1] [server2] [etc]',
        'desc'=>'Get uptime for OpenVZ Host(s)'
    ),
    'clear'=>array(
        'func'=>'clear_screen',
        'usage'=>'',
        'desc'=>'clears all output on screen'
    ),
    'raw'=>array(
        'func'=>'raw',
        'usage'=>'HOST COMMAND',
        'desc'=>'Runs COMMAND on HOST'
    ),
    'quit'=>array(
        'func'=>'quit_program',
        'usage'=>'',
        'desc'=>'Exit/Quit the program'
    ),
    'exit'=>array(
        'func'=>'quit_program',
        'usage'=>'',
        'desc'=>'Exit/Quit to the program'
    ),
    'help'=>array(
        'func'=>'help',
        'usage'=>'[command]',
        'desc'=>'Show this help page'
    )
);

?>