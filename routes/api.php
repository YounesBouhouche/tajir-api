<?php

Route::middleware(['throttle:api'])->prefix('v1')->group(base_path('routes/api_v1.php'));
