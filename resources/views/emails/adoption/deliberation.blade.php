<x-mail::message>
# Dear {{ $adopter->forename }}

Your @if(!$adoption0_return1) Adoption @else Return @endif request was deliberated as: **{{ Helper::getAdoptionStatus()[$adoption->status] }}**.

You can find more details below (*if apply*):

<x-mail::panel>
{{ $adoption->note }}
</x-mail::panel>

<x-mail::table>
| Name                      | Type                                           | 
| --------------------------|------------------------------------------------|
| {{ $adopter->forename }}  | {{ Helper::getAdopterType()[$adopter->type] }} |
| {{ $pet->name }}          | {{ Helper::getPetType()[$pet->type] }}         |
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
