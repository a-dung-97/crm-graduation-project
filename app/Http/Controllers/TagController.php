<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        return TagResource::collection(company()->tags()->select('id', 'name')
            ->where([['name', 'like', '%' . $request->query('name') . '%'], ['type', $request->query('type')]])->get());
    }
    public function getTags($type, $id)
    {
        return TagResource::collection(getModel($type, $id)->tags);
    }
    public function changeTags(Request $request, $type, $id)
    {
        $tags = $request->all();
        $newTags = [];
        foreach ($tags as $tag) {
            $newTag = Tag::where('name', $tag)->first();
            if (!$newTag) {
                $newTag = company()->tags()->create(['name' => $tag, "type" => $type]);
            }
            array_push($newTags, $newTag->id);
        }
        getModel($type, $id)->tags()->syncWithoutDetaching($newTags);
        return updated();
    }
    public function deleteTag(Request $request, $type, $id)
    {
        $tag = company()->tags()->where([['type', $type], ['name', $request->query('name')]])->first()->id;
        getModel($type, $id)->tags()->detach($tag);
        return response(null, 204);
    }
}
