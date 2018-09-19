<?php

namespace App\Transformers;

use App\Listing;
use App\Location;
use League\Fractal\TransformerAbstract;

class ListingTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'media_objects',
        'location'
    ];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Listing $listing)
    {
        return [
            'id'                  => (int) $listing->id,
            'acreage'             => $listing->ApxAcres, //maybe apxacres? was "acreage" if this is incorrect
            'area'                => $listing->area, //MLS_Area or SubArea
            'total_bathrooms'     => $listing->baths, //total doesn't exist
            'full_baths'          => $listing->Full_Bath,
            'half_baths'          => $listing->Half_Bath,
            'bedrooms'            => $listing->Bedroom,
            'ceiling_height'      => $listing->cib_ceiling_height, //maybe roof? Probably not
            'front_footage'       => $listing->RoadFrontFeet,
            'city'                => $listing->City,
            'co_listing_agent'    => $listing->CoList_DA_AGENT_ID,
            'co_listing_office'   => $listing->CoList_DO_NAME, //CoList_DO_OFFICE_ID
            'date_modified'       => $listing->sys_Last_modified,
            'directions'          => $listing->Directions,
            'construction'        => $listing->CF_B,
            'energy'              => $listing->CF_R,
            'exterior'            => $listing->CF_S,
            'forklift'            => $listing->ftr_forklift, //none?
            'full_address'        => $listing->Address, //turns out this doesn't include street number. There is no full address. Street_Num
            'hoa_included'        => $listing->ftr_hoaincl, //ones for association fees, but not for association
            'interior'            => $listing->CF_H,
            'lot_access'          => $listing->CF_X,
            'lot_descriptions'    => $listing->CF_Y,
            'ownership'           => $listing->ftr_ownership, //AgentOwnedYN or FractionalOwnershipYN
            'parking'             => $listing->CF_G,
            'projfacilities'      => $listing->CF_CC,
            'site_description'    => $listing->ftr_sitedesc, //none
            'transportation'      => $listing->ftr_transportation, //none
            'utilities'           => $listing->CF_L,
            'waterfront'          => $listing->CF_AA,
            'waterview'           => $listing->CF_BB,
            'zoning'              => $listing->Zoning, //zoning type is CF_Z if this is the right one
            'listing_agent'       => $listing->la_code, //none for this, either CoList_DA_AGENT_ID or CoSell_DA_AGENT_ID
            'legals'              => $listing->Supplement_Remarks,
            'legal_block'         => $listing->legal_block, //LotBlockUnit?
            'legal_lot'           => $listing->legal_lot, //LotBlockUnit?
            'legal_unit'          => $listing->legal_unit, //LotBlockUnit?
            'list_date'           => $listing->List_Date,
            'price'               => $listing->List_Price,
            'lot_dimensions'      => $listing->LotDimensions,
            'listing_office'      => $listing->lo_code, //CoList_DO_OFFICE_ID or CoSell_DO_OFFICE_ID
            'mls_account'         => $listing->mls_acct, //MST_MLS_NUMBER maybe
            'num_units'           => $listing->Unit_Num,
            'occupancy'           => $listing->Occupancy, //OR ImmediateOccupancyYN
            'parcel_id'           => $listing->ParcelID,
            'parking_spaces'      => (int) $listing->ParkingSpaces,
            'parking_type'        => $listing->parking_type, //none
            'photo_count'         => $listing->rets_photo_count,
            'photo_date_modified' => $listing->rets_photo_timestamp,
            'proj_name'           => $listing->proj_name, //none
            'prop_type'           => $listing->Property_Type,
            'show_address'        => $listing->VOWAddressDisplay, //public_show_address was the old one if this is wrong
            'remarks'             => $listing->Remarks, //or Agent_Remarks
            'hoa_fee'             => $listing->AssociationFeeAmount,
            'hoa_terms'           => $listing->res_hoa_term, //none
            'selling_agent'       => $listing->rets_selling_agt_id,
            'sold_on'             => $listing->Selling_Date,
            'sold_for'            => $listing->Selling_Price,
            'selling_office_code' => $listing->Selling_off_Number,
            'selling_office_name' => $listing->so_name, //don't think there is one
            'sqft'                => $listing->TotalSqFt, //there is also LivingSqFt
            'state'               => $listing->State,
            'status'              => $listing->Property_Status, //rets_status or ConstructionStatus are also options
            'stories'             => $listing->Stories,
            'street_name'         => $listing->Address,
            'street_num'          => (int) $listing->Street_Num,
            'subdivision'         => $listing->Subdivision,
            'sub_area'            => $listing->SubArea,
            'total_hc_sqft'       => $listing->tot_heat_sqft, //nothing CF_P for cooling and CF_Q for heating
            'unit_num'            => (int) $listing->Unit_Num,
            'waterfront_feet'     => $listing->WaterFrontFeet,
            'year_built'          => (int) $listing->ActualYearBuilt, //also EffectiveYearBuilt
            'zip'                 => $listing->ZipCode,
        ];
    }

    public function includeMediaObjects(Listing $listing)
    {
        $mediaObjects = $listing->mediaObjects->sortBy(function ($mediaObject) {
            return $mediaObject->media_order;
        }) ?? [];

        return $this->collection($mediaObjects, new MediaObjectTransformer);
    }

    public function includeLocation(Listing $listing)
    {
        $location = $listing->location ?? new Location();

        return $this->item($location, new LocationTransformer);
    }
}
