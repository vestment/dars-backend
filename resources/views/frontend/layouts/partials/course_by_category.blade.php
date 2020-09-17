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
        .iconss{
        font-size:90px;
    }
    
</style>
<section id="course-category" class="course-category-section">
    <div class="container">
        <div class="section-title mb45 headline ">
            <span class="subtitle text-uppercase">@lang('labels.frontend.layouts.partials.courses_categories')</span>
            <h4 class="title">@lang('labels.frontend.layouts.partials.browse_course_by_category')</h4>
        </div>
        @if($course_categories)
            <div class="category-item">
                <div class="row">
                    @foreach($course_categories->take(8) as $category)
                    @if ($category->slug != '911')
                        <div class="col-md-3 categ">
                            <a href="{{route('courses.category',['category'=>$category->slug])}}">
                                <div class=" text-center ">
                                    <div class="iconss">
                                        <i class=" {{$category->icon}}"></i>
                                    </div>
                                    <div class="">
                                        <h4>{{$category->getDataFromColumn('name')}}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                @endforeach
                <!-- /category -->
                </div>
            </div>
        @endif
    </div>
</section>
