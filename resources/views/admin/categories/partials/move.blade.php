{{--
    Reorder control for one row of the categories list.

    Shared by the top-level rows and the nested sub-category rows so both behave
    identically. The order is per-parent, so `isFirst` / `isLast` are the bounds of
    the row's OWN sibling group — a first child is at the top of its parent's list,
    not of the table.

    @param  \App\Models\Category  $row
    @param  string|int            $position  display label, e.g. 2 or "2.1"
    @param  int                   $total     siblings in this group
    @param  bool                  $isFirst
    @param  bool                  $isLast
--}}
<div class="d-flex align-items-center gap-1">
    <span class="badge bg-secondary" style="font-size: 0.65rem;"
          title="Position {{ $position }}{{ $total > 1 ? ' of ' . $total : '' }} in its list">
        {{ $position }}
    </span>
    <div class="d-flex flex-column">
        <form action="{{ route('admin.categories.move', [$row, 'up']) }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-1"
                    style="line-height: 1;" title="Move up" @disabled($isFirst)>
                <i class="fas fa-chevron-up" style="font-size: 0.6rem;"></i>
            </button>
        </form>
        <form action="{{ route('admin.categories.move', [$row, 'down']) }}" method="POST" class="m-0 mt-1">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-1"
                    style="line-height: 1;" title="Move down" @disabled($isLast)>
                <i class="fas fa-chevron-down" style="font-size: 0.6rem;"></i>
            </button>
        </form>
    </div>
</div>
