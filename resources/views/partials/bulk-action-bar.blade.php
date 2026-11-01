<x-bulk-action-bar :action-url="$actionUrl ?? null" :item-name="$itemName ?? 'item'" :item-name-plural="$itemNamePlural ?? 'items'" :actions="$actions ?? []" :selection-var="$selectionVar ?? 'selected'">
    {{ $slot ?? '' }}
</x-bulk-action-bar>
