<?php namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Entities\File;
use Modules\Media\Repositories\FileRepository;

class MediaController extends AdminBaseController
{
    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(FileRepository $file)
    {
        $this->file = $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $files = $this->file->all();

        return View::make('media::admin.index', compact('files'));
    }

    public function gridFiles()
    {
        $files = $this->file->all();

        return View::make('media::admin.grid', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return \View::make('media.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param File $file
     * @return Response
     */
    public function edit(File $file)
    {
        return View::make('media::admin.edit', compact('file'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param File $file
     * @return Response
     */
    public function update(File $file)
    {
        dd('form posted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
