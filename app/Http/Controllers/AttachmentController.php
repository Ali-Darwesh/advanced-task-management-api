<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAttachmentRequest;
use App\Models\Attachment;
use App\Models\Task;
use App\Services\AssetsService;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    protected $assetsService;

    public function __construct(AssetsService $assetsService)
    {
        $this->assetsService = $assetsService;
    }

    public function store(AddAttachmentRequest $request, $taskId)
    {
        $validatedData = $request->validated();
        $task = Task::findOrFail($taskId);

        if ($file = $request->file('file')) {
            $filePath = $file->store('attachments');

            $attachment = new Attachment([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
            ]);

            $task->attachments()->save($attachment);

            return response()->json(['message' => 'Attachment added successfully', 'attachment' => $attachment], 201);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }




    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        //
    }
}
