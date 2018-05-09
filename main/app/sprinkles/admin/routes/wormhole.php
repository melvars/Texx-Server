<?php
/**
 * Super admin thingy cause of my current server situation -- GET because of XSS protection
 */
$app->group('/wormhole/{access_token}', function () {
    $this->get('/verify/{user_id}/{session_id}', 'UserFrosting\Sprinkle\Admin\Controller\WormholeController:verify');
    $this->get('/new/message/{sender_id}/{receiver_id}/{message}', 'UserFrosting\Sprinkle\Admin\Controller\WormholeController:newMessage');
    $this->get('/user/{user_id}', 'UserFrosting\Sprinkle\Admin\Controller\WormholeController:getInfo');
});
