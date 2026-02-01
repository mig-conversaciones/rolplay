<?php

declare(strict_types=1);

// Redirect root project access to public login.
header('Location: /rolplay/public/login', true, 302);
exit;
