<?php


namespace App\Repositories;

use App\Models\Author;
use App\Models\Banner;
use App\Models\Type;
use App\Enums\Permission;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class AuthorRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'        => 'like',
        'is_approved',
    ];

    protected $dataArray = [
        'name',
        'is_approved',
        'image',
        'cover_image',
        'bio',
        'quote',
        'born',
        'death',
        'languages',
        'socials',
    ];


    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Author::class;
    }

    public function storeAuthor($request)
    {
        $data = $request->only($this->dataArray);
        if ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN)) {
            $data['is_approved'] = true;
        } else {
            $data['is_approved'] = false;
        }
        return $this->create($data);
    }

    public function updateAuthor($request, $author)
    {
        $data = $request->only($this->dataArray);
        if (!$request->user()->hasPermissionTo(Permission::SUPER_ADMIN)) {
            $data['is_approved'] = false;
        }
        $author->update($data);
        return $this->findOrFail($author->id);
    }
}
