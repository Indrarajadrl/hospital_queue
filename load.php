<?php
 require __DIR__ . '/vendor/autoload.php';

 $options = array(
   'cluster' => 'ap1',
   'useTLS' => true
 );
 $pusher = new Pusher\Pusher(
   '6b79efb8b6f3090d226f',
   '359430515216001dbb5a',
   '1107824',
   $options
 );

 $data['message'] = 'success';
 $pusher->trigger('my-channel', 'my-event', $data);
?>