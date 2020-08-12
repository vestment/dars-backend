<style>


.categ{
            text-align:center;
            padding:3%;
        }
        .categ:hover {
            /* background-color: linear-gradient(98deg, #52ADE1 0%, #625CA8 50%, #D2498B 100%); */
            background: -webkit-linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);  
    background: -moz-linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);  
    background: -o-linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);  
    background: linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);  

    -webkit-transition: background 1s ease-out;  
    -moz-transition: background 1s ease-out;  
    -o-transition: background 1s ease-out;  
    transition: background 1s ease-out;  
    color: white;
    border-radius: 5px;  
   


        }
</style>
<section id="course-category" class="course-category-section">
    <div class="container">
        <div class="section-title mb45 headline ">
            <span class="subtitle text-uppercase">@lang('labels.frontend.layouts.partials.courses_categories')</span>
            <h2>@lang('labels.frontend.layouts.partials.browse_course_by_category')</h2>
        </div>
        @if($course_categories)
            <div class="category-item">
                <div class="row">
                    @foreach($course_categories->take(8) as $category)
                        <div class="col-md-3 categ">
                            <a href="{{route('courses.category',['category'=>$category->slug])}}">
                                <div class=" text-center ">
                                    <div class="category-icon">
                                        <i class="text-gradiant {{$category->icon}}"></i>
                                    </div>
                                    <div class="category-title">
                                        <h4>{{$category->name}}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                @endforeach
                <!-- /category -->
                </div>
            </div>
        @endif
    </div>
</section>
