<?php

namespace App\Http\Controllers\Admin\Users;

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
            //$extension = $this->request->file('file')->getClientOriginalExtension();
            $name = $this->request->file('file')->getClientOriginalName();

            //store the file
            $path = $this->request->file('file')->storeAs('imports', $name);

            //Run the corresponding job
            $jobId = $this->dispatch($this->userFileReaderJob->init($path));

            return response()->json(['job_id' => $jobId]);
        }

        throw new Exception('Class not found for this import extension');
    }
}
