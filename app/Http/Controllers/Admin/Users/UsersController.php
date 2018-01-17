<?php

namespace App\Http\Controllers\Admin\Users;

use App\Helpers\Import\ImportHelper;
use App\Jobs\UserFileReaderJob;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Exception;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UsersController extends Controller
{
    use DispatchesJobs;

    private $userFileReaderJob;
    private $request;

    /**
     * UserController constructor.
     *
     * @param Request           $request
     * @param UserFileReaderJob $job
     */
    public function __construct(Request $request, UserFileReaderJob $job)
    {
        $this->request = $request;

        $this->userFileReaderJob = $job;
    }

    /**
     * File import
     */
    public function import()
    {
        //Valid upload?
        if ($this->request->file('file')->isValid()) {
            $name = $this->request->file('file')->getClientOriginalName();

            //store the file
            $path = $this->request->file('file')->storeAs(ImportHelper::IMPORT_LOCATION, $name);

            //Run the corresponding job
            $this->dispatch($this->userFileReaderJob->init($path));

            //Return a job id
            return response()->json(['status' => 'ok']);
        }

        throw new Exception('Class not found for this import extension');
    }
}
