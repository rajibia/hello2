<?php

namespace App\Repositories;

use App\Models\Insurance;
use App\Models\InsuranceDisease;
use App\Models\InsurancePackage;
use Arr;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Validator;
use Spatie\MediaLibrary\Support\MediaStream;
use Illuminate\Support\Str;

/**
 * Class InsuranceRepository
 *
 * @version February 22, 2020, 9:01 am UTC
 */
class InsuranceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        // 'service_tax',
        // 'insurance_no',
        'insurance_code',

        'other_identification',
        'card_type',
        'claim_code_count',
        'membership_no_count',
        'card_serial_no_count',
        'visit_per_month',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Insurance::class;
    }

    public function store($input)
    {
        /*
        $diseaseItemInputArray = Arr::only($input, ['disease_name', 'disease_charge']);

        $insurance = Insurance::create(Arr::except($input, ['disease_name', 'disease_charge']));

        $diseaseItemInput = $this->prepareInputForDiseaseItem($diseaseItemInputArray);

        foreach ($diseaseItemInput as $key => $data) {
            $data['insurance_id'] = $insurance->id;
            $validator = Validator::make($data, InsuranceDisease::$rules);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }
            $data['disease_charge'] = removeCommaFromNumbers($data['disease_charge']);
            $disease = InsuranceDisease::create($data);
        }

        */
        $packageItemInputArray = Arr::only($input, ['package_name']);

        $insurance = Insurance::create(Arr::except($input, ['package_name']));

        if (isset($input['image']) && ! empty($input['image'])) {
            // $fileExtension = getFileName('Insurance', $input['image']);
            // $insurance->addMedia($input['image'])->usingFileName($fileExtension)->toMediaCollection(Insurance::COLLECTION_LOGO_PICTURES,
            //     config('app.media_disc'));
            $fileExtension = pathinfo($input['image']->getClientOriginalName(), PATHINFO_EXTENSION);
            $uniqueFilename = 'Insurance_' . Str::random(10) . '.' . $fileExtension;
            $media = $insurance->addMedia($input['image'])
                ->usingFileName($uniqueFilename)
                ->toMediaCollection(Insurance::COLLECTION_LOGO_PICTURES, config('app.media_disc'));
            $mediaUrl = $media->getUrl();
            
            $insurance->update(['image' => $mediaUrl]);
        }

        $diseaseItemInput = $this->prepareInputForDiseaseItem($packageItemInputArray);

        foreach ($diseaseItemInput as $key => $data) {
            if (!is_null($data['package_name'])) {
                $data['insurance_id'] = $insurance->id;
                $validator = Validator::make($data, InsurancePackage::$rules);

                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                $disease = InsurancePackage::create($data);
            }
        }
        return true;
    }

    public function prepareInputForDiseaseItem($input)
    {
        $items = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $items[$index][$key] = $value;
            }
        }

        return $items;
    }

    public function getDisease()
    {
        $disease = InsuranceDisease::all();

        return $disease;
    }

    public function getPackage()
    {
        $package = InsurancePackage::all();

        return $package;
    }

    public function getInsuranceDisease($insuranceId)
    {
        $diseases = InsuranceDisease::whereInsuranceId($insuranceId)->get();

        return $diseases;
    }

    public function getInsurancePackage($insuranceId)
    {
        $package = InsurancePackage::whereInsuranceId($insuranceId)->get();

        return $package;
    }

    public function update($insurance, $input)
    {
        /*
        $diseaseItemInputArray = Arr::only($input, ['disease_name', 'disease_charge']);

        $insurance->update($input);

        $disease = InsuranceDisease::whereInsuranceId($insurance->id);
        $disease->delete();
        $diseaseItemInput = $this->prepareInputForDiseaseItem($diseaseItemInputArray);

        foreach ($diseaseItemInput as $key => $data) {
            $data['insurance_id'] = $insurance->id;
            $validator = Validator::make($data, InsuranceDisease::$rules);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }
            $data['disease_charge'] = removeCommaFromNumbers($data['disease_charge']);
            InsuranceDisease::create($data);
        }
        */
        $packageItemInputArray = Arr::only($input, ['package_name']);

        $insurance->update($input);

        if (isset($input['image']) && ! empty($input['image'])) {
        //     $insurance->clearMediaCollection(Insurance::COLLECTION_LOGO_PICTURES);
        //     $fileExtension = getFileName('Insurance', $input['image']);   
        //     $insurance->addMedia($input['image'])->usingFileName($fileExtension)->toMediaCollection(Insurance::COLLECTION_LOGO_PICTURES,
        //         config('app.media_disc'));
        //     $insurance->update(['updated_at' => Carbon::now()->timestamp]);
        // }
            $insurance->clearMediaCollection(Insurance::COLLECTION_LOGO_PICTURES);
            $fileExtension = getFileName('Insurance', $input['image']);
            
            $media = $insurance->addMedia($input['image'])
                ->usingFileName($fileExtension)
                ->toMediaCollection(Insurance::COLLECTION_LOGO_PICTURES, config('app.media_disc'));
            $mediaUrl = $media->getUrl();
        
            $insurance->update(['image' => $mediaUrl, 'updated_at' => Carbon::now()->timestamp]);
        }

        if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
            removeFile($insurance, Insurance::COLLECTION_LOGO_PICTURES);
        }

        $package = InsurancePackage::whereInsuranceId($insurance->id);
        $package->delete();
        $packageItemInput = $this->prepareInputForDiseaseItem($packageItemInputArray);

        foreach ($packageItemInput as $key => $data) {
            if (!is_null($data['package_name'])) {
                $data['insurance_id'] = $insurance->id;
                $validator = Validator::make($data, InsurancePackage::$rules);

                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                Insurancepackage::create($data);
            }
        }

        return true;
    }

    public function delete($insuranceId)
    {
        /*
        try {
            $insurance = Insurance::find($insuranceId);
            $insurance->delete();
            $insuranceDisease = InsuranceDisease::whereInsuranceId($insuranceId);
            $insuranceDisease->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        */
        try {
            $insurance = Insurance::find($insuranceId);
            $insurance->delete();
            $insurancePackage = InsurancePackage::whereInsuranceId($insuranceId);
            $insurancePackage->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
