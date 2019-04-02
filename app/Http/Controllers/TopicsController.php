<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request)
	{
		$topics = Topic::withOrder($request->order)->paginate(20);
		return view('topics.index', compact('topics'));
	}

    public function show(Request $request, Topic $topic)
    {
        if (! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
	    $topic->fill($request->all());
	    $topic->user_id = \Auth::id();
	    $topic->save();

		return redirect()->to($topic->link())->with('success', 'Created successfully.');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);

        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', 'Deleted successfully.');
	}

	public function uploadImage(Request $request, ImageUploadHandler $handler)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => '',
        ];

        if ($file = $request->upload_file) {
            $res = $handler->save($file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($res) {
                $data = [
                    'success' => true,
                    'msg' => '上传成功',
                    'file_path' => $res['path'],
                ];
            }
        }
        return $data;
    }
}
