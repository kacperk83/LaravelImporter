<?php

namespace App\Http\Controllers\Admin\Users;

use App\Helpers\Import\ImportHelper;
use App\Jobs\UserFileReaderJob;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

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

    /**
     * @var UserFileReaderJob $userFileReaderJob
     */
    private $userFileReaderJob;

    /**
     * @var Request $request
     */
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
        if (!$this->request->file('file')->isValid()) {
            return response()->json(['status' => 'error', 'message' => 'file invalid']);
        }

        //get the original filename
        $name = $this->request->file('file')->getClientOriginalName();

        //store the file
        $path = $this->request->file('file')->storeAs(ImportHelper::IMPORT_LOCATION, $name);

        //Run the corresponding job
        $this->dispatch($this->userFileReaderJob->init($path));

        //Return an OK
        return response()->json(['status' => 'ok', 'message' => 'import successful']);
    }
}
