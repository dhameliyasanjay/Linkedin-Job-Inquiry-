<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LocationApiController extends Controller
{
    /**
     * All Indian states.
     */
    protected array $states = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
        'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu',
        'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry',
    ];

    /**
     * Cities grouped by Indian state.
     */
    protected array $citiesByState = [
        'Andhra Pradesh'       => ['Visakhapatnam','Vijayawada','Guntur','Nellore','Kurnool','Tirupati','Kakinada','Rajamahendravaram','Kadapa','Anantapur'],
        'Arunachal Pradesh'    => ['Itanagar','Naharlagun','Pasighat','Tawang','Ziro'],
        'Assam'                => ['Guwahati','Silchar','Dibrugarh','Jorhat','Nagaon','Tinsukia','Tezpur','Bongaigaon','Dhubri','Lakhimpur'],
        'Bihar'                => ['Patna','Gaya','Bhagalpur','Muzaffarpur','Purnia','Darbhanga','Bihar Sharif','Arrah','Begusarai','Katihar'],
        'Chhattisgarh'         => ['Raipur','Bhilai','Bilaspur','Korba','Durg','Rajnandgaon','Jagdalpur','Ambikapur','Raigarh','Dhamtari'],
        'Goa'                  => ['Panaji','Margao','Vasco da Gama','Mapusa','Ponda','Bicholim','Curchorem','Sanquelim'],
        'Gujarat'              => ['Ahmedabad','Surat','Vadodara','Rajkot','Bhavnagar','Jamnagar','Junagadh','Gandhinagar','Anand','Navsari','Morbi','Mehsana','Bharuch','Vapi'],
        'Haryana'              => ['Faridabad','Gurgaon','Panipat','Ambala','Yamunanagar','Rohtak','Hisar','Karnal','Sonipat','Panchkula'],
        'Himachal Pradesh'     => ['Shimla','Dharamshala','Mandi','Solan','Nahan','Kullu','Hamirpur','Bilaspur','Chamba','Una'],
        'Jharkhand'            => ['Ranchi','Jamshedpur','Dhanbad','Bokaro Steel City','Deoghar','Phusro','Hazaribagh','Giridih','Ramgarh','Medininagar'],
        'Karnataka'            => ['Bengaluru','Mysuru','Hubli','Mangaluru','Belagavi','Kalaburagi','Davanagere','Ballari','Vijayapura','Shivamogga','Tumkur','Udupi'],
        'Kerala'               => ['Thiruvananthapuram','Kochi','Kozhikode','Thrissur','Kollam','Palakkad','Alappuzha','Malappuram','Kannur','Kasaragod'],
        'Madhya Pradesh'       => ['Indore','Bhopal','Jabalpur','Gwalior','Ujjain','Sagar','Rewa','Satna','Dewas','Chhindwara','Ratlam','Burhanpur'],
        'Maharashtra'          => ['Mumbai','Pune','Nagpur','Thane','Nashik','Aurangabad','Solapur','Kolhapur','Amravati','Nanded','Sangli','Jalgaon','Akola','Latur','Dhule'],
        'Manipur'              => ['Imphal','Thoubal','Bishnupur','Churachandpur','Kakching'],
        'Meghalaya'            => ['Shillong','Tura','Jowai','Nongstoin','Baghmara'],
        'Mizoram'              => ['Aizawl','Lunglei','Saiha','Champhai','Kolasib'],
        'Nagaland'             => ['Kohima','Dimapur','Mokokchung','Tuensang','Wokha'],
        'Odisha'               => ['Bhubaneswar','Cuttack','Rourkela','Berhampur','Sambalpur','Puri','Balasore','Bhadrak','Jharsuguda','Bargarh'],
        'Punjab'               => ['Ludhiana','Amritsar','Jalandhar','Patiala','Bathinda','Mohali','Firozpur','Pathankot','Hoshiarpur','Batala'],
        'Rajasthan'            => ['Jaipur','Jodhpur','Kota','Bikaner','Udaipur','Ajmer','Bhilwara','Alwar','Bharatpur','Sikar'],
        'Sikkim'               => ['Gangtok','Namchi','Mangan','Gyalshing','Rangpo'],
        'Tamil Nadu'           => ['Chennai','Coimbatore','Madurai','Tiruchirappalli','Salem','Tirunelveli','Erode','Tiruppur','Vellore','Thoothukudi','Ambattur'],
        'Telangana'            => ['Hyderabad','Warangal','Nizamabad','Karimnagar','Khammam','Ramagundam','Mahbubnagar','Nalgonda','Suryapet','Miryalaguda'],
        'Tripura'              => ['Agartala','Dharmanagar','Udaipur','Kailashahar','Belonia'],
        'Uttar Pradesh'        => ['Lucknow','Kanpur','Ghaziabad','Agra','Meerut','Varanasi','Allahabad','Bareilly','Aligarh','Moradabad','Saharanpur','Gorakhpur','Noida','Firozabad','Jhansi','Mathura','Shahjahanpur','Rampur','Muzaffarnagar','Loni'],
        'Uttarakhand'          => ['Dehradun','Haridwar','Roorkee','Haldwani','Rudrapur','Kashipur','Rishikesh','Kotdwar','Ramnagar','Pithoragarh'],
        'West Bengal'          => ['Kolkata','Howrah','Asansol','Siliguri','Durgapur','Bardhaman','Malda','Baharampur','Habra','Kharagpur','Shantipur'],
        'Andaman and Nicobar Islands' => ['Port Blair','Diglipur','Mayabunder'],
        'Chandigarh'           => ['Chandigarh'],
        'Dadra and Nagar Haveli and Daman and Diu' => ['Daman','Diu','Silvassa'],
        'Delhi'                => ['New Delhi','Dwarka','Rohini','Janakpuri','Saket','Lajpat Nagar','Karol Bagh','Connaught Place'],
        'Jammu and Kashmir'    => ['Srinagar','Jammu','Anantnag','Sopore','Baramulla','Kathua','Udhampur'],
        'Ladakh'               => ['Leh','Kargil'],
        'Lakshadweep'          => ['Kavaratti','Andrott','Minicoy'],
        'Puducherry'           => ['Puducherry','Karaikal','Mahe','Yanam'],
    ];

    /**
     * Return all Indian states as a sorted JSON array.
     */
    public function indianStates(): JsonResponse
    {
        $states = $this->states;
        sort($states);
        return response()->json($states);
    }

    /**
     * Return cities for a given Indian state.
     */
    public function indianCitiesByState(string $state): JsonResponse
    {
        $cities = $this->citiesByState[$state] ?? [];
        sort($cities);
        return response()->json($cities);
    }

    /**
     * Return all Indian cities (flat list, for backward compatibility).
     */
    public function indianCities(): JsonResponse
    {
        $all = [];
        foreach ($this->citiesByState as $cities) {
            foreach ($cities as $city) {
                $all[] = $city;
            }
        }
        sort($all);
        return response()->json(array_values(array_unique($all)));
    }
}
