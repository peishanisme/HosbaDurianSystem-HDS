<div>
    <div class="absolute -top-10 -left-10 w-24 h-24 opacity-10 hidden md:block"
        style='background-image: url("./api/searchImage?query=hand drawn fruit pattern, organic line art, green color, simple illustration"); background-size: contain; background-repeat: no-repeat;'>
    </div>
    <div class="absolute -bottom-10 -right-10 w-24 h-24 opacity-10 hidden md:block"
        style='background-image: url("./api/searchImage?query=hand drawn leaf pattern, organic line art, green color, simple illustration"); background-size: contain; background-repeat: no-repeat;'>
    </div>

    <!-- product title section -->
    <section id="home" class="mb-12 text-center">
        <div class="relative">
            <div class="absolute -top-6 left-1/4 w-16 h-16 opacity-20"
                style='background-image: url("./api/searchImage?query=hand drawn durian sketch, organic line art, light green color, simple illustration"); background-size: contain; background-repeat: no-repeat;'>
            </div>
            <h1
                class="text-[clamp(2.5rem,6vw,4rem)] font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary mb-4 relative z-10 ">
                {{ $ipfsMetadata['species'] ?? $fruit->tree->species->name }}
            </h1>
            <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-48 h-6 bg-accent/30 rounded-full -z-10"
                style="clip-path: polygon(10% 0%, 90% 0%, 100% 50%, 90% 100%, 10% 100%, 0% 50%);"></div>
            <div class="absolute -top-6 right-1/4 w-16 h-16 opacity-20"
                style='background-image: url("./api/searchImage?query=hand drawn leaf sketch, organic line art, light green color, simple illustration"); background-size: contain; background-repeat: no-repeat;'>
            </div>
        </div>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg">
            Selected durians from Malaysia's premium growing regions, naturally ripened, with a rich flavor and intense
            aroma
        </p>
        <div class="mt-8 rounded-2xl overflow-hidden shadow-lg max-w-3xl mx-auto">
            <img alt="durian" class="w-full h-70 md:h-80 object-cover"
                src="https://design.gemcoder.com/staticResource/echoAiSystemImages/d7dc2b88833636e01f0c1de8d3e3d7ce.png" />
        </div>
    </section>

    <!-- product details -->
    <section id="product-details" class="mb-12 max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl shadow-lg p-6 md:p-8 card-hover border-2 border-secondary/20 relative overflow-hidden bg-opacity-90 backdrop-blur-sm"
            style="box-shadow: 0 4px 20px rgba(106, 153, 78, 0.1); border-style: dotted;">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold hunter-green flex items-center">
                    <i class="fas fa-crown text-accent mr-2"></i>
                    Product Details
                </h2>

                @php
                    $verificationMessages = [
                        'not_published' => 'This product has not yet been published for verification.',
                        'record_not_found' => 'This product record could not be found.',
                        'data_mismatch' => 'The product information may have been changed or is incorrect.',
                        'temporarily_unavailable' => 'Verification service is temporarily unavailable.',
                    ];
                @endphp

                @if ($isVerified)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700">
                        ✅ Verified Product
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 relative group">
                        ⚠️ Verification Failed

                        <i class="fas fa-info-circle ml-2 cursor-pointer"></i>

                        <div
                            class="absolute left-1/2 top-full mt-2 w-72 -translate-x-1/2
                   bg-gray-800 text-white text-xs rounded-lg px-4 py-3
                   opacity-0 group-hover:opacity-100 transition z-50">
                            <p class="font-semibold mb-1">Why this may happen:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>{{ $verificationMessages[$verificationReasonCode] ?? 'Unable to verify this product.' }}
                                </li>
                                <li>Please contact us for more information.</li>
                            </ul>
                        </div>
                    </span>
                @endif

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="bg-secondary/20 p-3 rounded-full mr-4">
                        <i class="fas fa-barcode hunter-green"> </i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Fruit Tag</h3>
                        <p class="text-gray-600">{{ $ipfsMetadata['id'] ?? $fruit->fruit_tag }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-secondary/20 p-3 rounded-full mr-4">
                        <i class="fas fa-tree hunter-green"> </i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Tree Tag</h3>
                        <p class="text-gray-600">{{ $ipfsMetadata['tree_origin'] ?? $fruit->tree->tree_tag }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-secondary/20 p-3 rounded-full mr-4">
                        <i class="fas fa-calendar-alt hunter-green"> </i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Harvest Date</h3>
                        <p class="text-gray-600">{{ $ipfsMetadata['date'] ?? $fruit->harvested_at }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-secondary/20 p-3 rounded-full mr-4">
                        <i class="fas fa-weight hunter-green"> </i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Weight</h3>
                        <p class="text-gray-600">{{ $ipfsMetadata['weight'] ?? $fruit->weight . ' kg' }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-secondary/20 p-3 rounded-full mr-4">
                        <i class="fas fa-star hunter-green"> </i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Grade</h3>
                        <p class="text-gray-600">{{ $ipfsMetadata['grade'] ?? $fruit->grade }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-secondary/20 p-3 rounded-full mr-4">
                        <i class="fas fa-link hunter-green"> </i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Blockchain Record ID</h3>
                        <p class="text-gray-600 break-all">
                            {{ $fruit->tx_hash ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-100">
                <a class="hunter-green hover:text-dark flex items-center font-medium transition-colors"
                    href="https://amoy.polygonscan.com/tx/{{ $fruit->tx_hash }}" target="_blank">
                    <span> View Full Blockchain Traceability Record </span>
                    <i class="fas fa-arrow-right ml-2"> </i>
                </a>
            </div>
        </div>
    </section>

    <!-- [MODULE] k6l_感谢信息区域 -->
    <section
        class="mb-12 max-w-3xl mx-auto bg-secondary/10 rounded-3xl p-4 md:p-8 border-2 border-secondary/20 shadow-md"
        style="border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; border-style: dashed;">
        <div
            class="flex flex-col md:flex-row items-start md:items-center -mt-2 mb-3 space-y-3 md:space-y-0 md:space-x-4 text-center md:!text-left">
            <div class="bg-accent/50 p-3 rounded-full self-center md:self-auto md:mx-4">
                <i class="fas fa-heart sage-green text-xl"> </i>
            </div>
            <div>
                <h2 class="text-xl font-bold hunter-green mb-2">For Our Valued Customers</h2>
                <p class="text-gray-700 px-4 md:!px-0">
                    Grown with care,delivered with integrity. Thank you for supporting us!
                </p>
            </div>
        </div>
    </section>

    <!-- [MODULE] m7n_反馈区域 -->
    <section id="feedback-section" class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl shadow-md p-6 md:p-8 card-hover border-2 border-accent/30 relative overflow-hidden"
            style="border-radius: 25px; border-style: dotted;">
            <h2 class="text-2xl font-bold hunter-green mb-6 flex items-center">
                <i class="fas fa-comment-dots mr-2 text-accent"> </i>
                We Value Your Feedback
            </h2>
            <form id="feedback-form" wire:submit.prevent="submit">
                <div class="mb-6">
                    <label class="block hunter-green font-medium mb-2" for="feedback">
                        Please share your experience with this purchase
                    </label>

                    <textarea id="feedback" name="feedback" rows="5" wire:model.defer="feedback"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none resize-none shadow-sm hover:shadow-md transition-shadow"
                        placeholder="What do you think about the taste, aroma, and ripeness of the durian? Any suggestions are welcome..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button
                        class="bg-gradient-to-r from-accent to-goldLight text-dark font-medium py-3 px-6 rounded-xl transition-all duration-300 flex items-center shadow-md hover:shadow-lg hover:shadow-accent/30 transform hover:-translate-y-0.5"
                        type="submit">
                        <span class="hunter-green"> Submit Feedback </span>
                        <i class="fas fa-paper-plane ml-2"> </i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" id="feedback-modal" wire:ignore>
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-900 scale-95 opacity-0 shadow-xl"
            id="modal-content" wire:ignore>
            <div class="text-center">
                <div class="bg-green-400 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-white text-2xl"> </i>
                </div>
                <h3 class="text-2xl font-bold hunter-green mb-2">Feedback Submitted Successfully</h3>
                <p class="text-gray-600 mb-6">
                    Thank you for your valuable feedback! We will carefully consider your opinions and continuously
                    improve our products and services.
                </p>
                <button
                    class="bg-gradient-to-r from-accent to-goldLight hover:shadow-lg hover:shadow-accent/30 transform hover:-translate-y-0.5 hunter-green font-medium py-2 px-6 rounded-xl transition-colors"
                    id="close-modal">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- ERROR MODAL -->
    <div id="error-modal" wire:ignore class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 shadow-xl"
            id="error-modal-content" wire:ignore>
            <div class="text-center">
                <div class="bg-red-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-red-600 mb-2">Error</h3>

                <p class="text-gray-600 mb-6" id="error-message">
                    Something went wrong. Please try again.
                </p>

                <button class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-xl"
                    id="close-error-modal">
                    Close
                </button>
            </div>
        </div>
    </div>

</div>
