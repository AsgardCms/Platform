<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ $user->present()->gravatar() }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <?php if ($user->present()->fullname() != ' '): ?>
                    <p><?= $user->present()->fullname(); ?></p>
                <?php else: ?>
                    <p><em>Complete your profile.</em></p>
                <?php endif; ?>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php foreach($items as $i => $item): ?>
                <?php if (is_object($item)): ?>
                    <li class="treeview {{ Request::is($item[0]['request']) ? 'active' : ''}}">
                        <a href="#">
                            <i class="{{ $item[0]['icon-class'] }}"></i> <span>{{ $item[0]['title'] }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <?php $item->shift(); ?>
                        <ul class="treeview-menu">
                            <?php foreach($item as $subItem): ?>
                                <li class="{{ Request::is($subItem['request']) ? 'active' : ''}}">
                                    <a href="{{ URL::route($subItem['route']) }}"><i class="{{$subItem['icon-class']}}"></i> {{ $subItem['title'] }}</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else: ?>
                <li class="{{ Request::is($item['request']) ? 'active' : ''}}">
                    <a href="{{ URL::route($item['route']) }}">
                        <i class="{{ $item['icon-class'] }}"></i> <span>{{ $item['title'] }}</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>