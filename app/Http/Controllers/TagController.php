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
    public function changeTags(Request $request, $type)
    {
        $tags = $request->tags;
        $objs = $request->objects;
        $newTags = [];
        foreach ($tags as $tag) {
            $newTag = company()->tags()->where('name', $tag)->first();
            if (!$newTag) {
                $newTag = company()->tags()->create(['name' => $tag, "type" => $type]);
            }
            array_push($newTags, $newTag);
        }
        foreach ($newTags as $tag) {
            if ($type == 'customer') $tag->customers()->syncWithoutDetaching($objs);
            else $tag->leads()->syncWithoutDetaching($objs);
        }
        return updated();
    }
    public function deleteTag(Request $request, $type)
    {
        $objs = $request->objects;
        $tags = company()->tags()->where('type', $type)->whereIn('name', $request->tags)->get();
        foreach ($tags as $tag) {
            if ($type == 'customer') $tag->customers()->detach($objs);
            else $tag->leads()->detach($objs);
        }
        return response(null, 204);
    }
}
