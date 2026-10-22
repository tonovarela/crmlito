<div class="overflow-x-auto w-full">
    <table class="min-w-full bg-white shadow-md table-striped">
        <thead class="bg-gray rounded-full">
            {{ $thead }}
        </thead>

        <tbody>
            {{ $tbody }}
        </tbody>
    </table>
</div>

<style>
    .table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }
    .table-striped tbody tr:nth-child(even) {
        background-color: #ffffff;
    }
</style>