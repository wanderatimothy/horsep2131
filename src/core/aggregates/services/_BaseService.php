<?php

namespace core\aggregates\services;

use infrastructure\database\specifications\Repositories\AccountRepository;

class _BaseService {

    protected AccountRepository $accountRepository;

    protected  $fileUploadService;

    protected NotificationService $notificationService;
    
    public function __construct()
    {
        $this->accountRepository = new AccountRepository();

        $this->fileUploadService = new FileUploadService;
        
        $this->notificationService = new NotificationService();
    }

 

}