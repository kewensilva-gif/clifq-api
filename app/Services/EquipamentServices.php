<?php
namespace App\Services;

use App\DTOs\EquipamentDTO;
use App\Enums\EquipamentEnum;
use App\Http\Requests\EquipamentStoreRequest;
use App\Http\Requests\EquipamentUpdateRequest;
use App\Models\Equipament;
use Illuminate\Support\Facades\Storage;

class EquipamentServices
{
    public function list()
    {
        $paginated = Equipament::paginate(8);

        $data = $paginated->getCollection()
            ->map(fn($loan) => EquipamentDTO::fromModel($loan));

        $paginated->setCollection($data);

        return response()->json([
            'data' => $paginated,
            'status_list' => EquipamentEnum::options(),
        ]);
    }

    public function store(EquipamentStoreRequest $request) {
        $equipament = $request->validated();

        if($request->has('image')) {
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);
            $equipament['image'] = 'images/'.$filename;
        }

        Equipament::create($equipament);

        return $equipament;
    }

    public function update(EquipamentUpdateRequest $request, Equipament $equipament) {
        $requestValidated = $request->validated();
        if($request->has('image')) {
            if(Storage::disk('public')->exists($equipament['image'])) {
                Storage::disk('public')->delete($equipament['image']);
            }
            
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);
            
            $requestValidated['image'] = 'images/'.$filename;
        }
        
        return $equipament->update($requestValidated);
    }

    public function destroy(Equipament $equipament) {
        Storage::delete('public/'.$equipament['image']);
        $response = $equipament->delete();

        return $response;
    }
}