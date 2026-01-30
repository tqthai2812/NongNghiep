@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-warning">
                    <span class="material-icons">equalizer</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Visits</strong></p>
                <h3 class="card-title">70,340</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons text-info">info</i>
                    <a href="#pablo">See detailed report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-rose">
                    <span class="material-icons">shopping_cart</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Orders</strong></p>
                <h3 class="card-title">102</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">local_offer</i> Product-wise sales
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-success">
                    <span class="material-icons">attach_money</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Revenue</strong></p>
                <h3 class="card-title">$23,100</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Weekly sales
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-info">
                    <span class="material-icons">follow_the_signs</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Followers</strong></p>
                <h3 class="card-title">+245</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">update</i> Just Updated
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-lg-7 col-md-12">
        <div class="card" style="min-height: 485px">
            <div class="card-header card-header-text">
                <h4 class="card-title">Employees Stats</h4>
                <p class="category">New employees on 15th December, 2016</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table table-hover">
                    <thead class="text-primary">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Bob Williams</td>
                            <td>$23,566</td>
                            <td>USA</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Mike Tyson</td>
                            <td>$10,200</td>
                            <td>Canada</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Tim Sebastian</td>
                            <td>$32,190</td>
                            <td>Netherlands</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Philip Morris</td>
                            <td>$31,123</td>
                            <td>Korea, South</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Minerva Hooper</td>
                            <td>$23,789</td>
                            <td>South Africa</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Hulk Hogan</td>
                            <td>$43,120</td>
                            <td>Netherlands</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Angelina Jolie </td>
                            <td>$12,140</td>
                            <td>Australia</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-md-12">
        <div class="card" style="min-height: 485px">
            <div class="card-header card-header-text">
                <h4 class="card-title">Activities</h4>
            </div>
            <div class="card-content">
                <div class="streamline">
                    <div class="sl-item sl-primary">
                        <div class="sl-content">
                            <small class="text-muted">5 mins ago</small>
                            <p>Williams has just joined Project X</p>
                        </div>
                    </div>
                    <div class="sl-item sl-danger">
                        <div class="sl-content">
                            <small class="text-muted">25 mins ago</small>
                            <p>Jane has sent a request for access to the project folder</p>
                        </div>
                    </div>
                    <div class="sl-item sl-success">
                        <div class="sl-content">
                            <small class="text-muted">40 mins ago</small>
                            <p>Kate added you to her team</p>
                        </div>
                    </div>
                    <div class="sl-item">
                        <div class="sl-content">
                            <small class="text-muted">45 minutes ago</small>
                            <p>John has finished his task</p>
                        </div>
                    </div>
                    <div class="sl-item sl-warning">
                        <div class="sl-content">
                            <small class="text-muted">55 mins ago</small>
                            <p>Jim shared a folder with you</p>
                        </div>
                    </div>
                    <div class="sl-item">
                        <div class="sl-content">
                            <small class="text-muted">60 minutes ago</small>
                            <p>John has finished his task</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection