<loader 
    is="loader"
    inline-template

    :show="{{ $show }}" 
    message="{{ $message }}">
    <div v-if="show" :class="overlay ? 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center' : 'inline-flex items-center space-x-2'">
        <div class="flex flex-col items-center">
            <svg class="animate-spin h-8 w-8 text-white mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <p class="text-white text-sm">@{{ message }}</p>
        </div>
    </div>
</loader>