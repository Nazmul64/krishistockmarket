<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Board of Directors & Leadership Messages
        if (!Schema::hasTable('board_members')) {
            Schema::create('board_members', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('designation');
                $table->string('designation_bn')->nullable();
                $table->string('role_type')->default('director'); // chairman, md, ceo, marketing_head, finance_head, director, advisor
                $table->string('photo')->nullable();
                $table->text('bio')->nullable();
                $table->text('administrative_role')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('speech_title')->nullable();
                $table->longText('speech_message')->nullable();
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Corporate Profile (History, Vision, Mission, Goals, Future Plans, Achievements)
        if (!Schema::hasTable('corporate_profiles')) {
            Schema::create('corporate_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('section_key')->unique(); // history, vision_mission, goals_objectives, future_plans, achievements
                $table->string('title_bn');
                $table->string('title_en')->nullable();
                $table->longText('content_bn')->nullable();
                $table->longText('content_en')->nullable();
                $table->string('featured_image')->nullable();
                $table->json('extra_points')->nullable(); // bullet items or milestones
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Corporate Projects & Field Activities Implementation
        if (!Schema::hasTable('corporate_activities')) {
            Schema::create('corporate_activities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('project_code')->unique();
                $table->string('category')->default('ongoing'); // ongoing, completed, planned
                $table->string('sector_type')->nullable(); // agriculture, dairy, fishery, greenhouse, property, etc.
                $table->string('location')->nullable();
                $table->integer('progress_percentage')->default(0); // 0 to 100
                $table->decimal('budget', 16, 2)->default(0);
                $table->text('description')->nullable();
                $table->longText('field_report')->nullable();
                $table->string('featured_image')->nullable();
                $table->json('gallery_images')->nullable();
                $table->string('video_url')->nullable();
                $table->date('start_date')->nullable();
                $table->date('completion_date')->nullable();
                $table->string('status')->default('active'); // active, completed, upcoming, on_hold
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });
        }

        // 4. Investment Sectors (10 Specified Sectors)
        if (!Schema::hasTable('investment_sectors')) {
            Schema::create('investment_sectors', function (Blueprint $table) {
                $table->id();
                $table->string('sector_no'); // 01, 02, ... 10
                $table->string('name_bn');
                $table->string('name_en');
                $table->string('investment_range'); // e.g. ২–১০ লাখ টাকা
                $table->decimal('min_amount', 14, 2)->default(200000);
                $table->decimal('max_amount', 14, 2)->default(1000000);
                $table->string('duration'); // e.g. কর্তৃপক্ষ নির্ধারিত, ১০০ দিন
                $table->string('icon')->nullable();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->text('profit_loss_policy')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('order_num')->default(0);
                $table->timestamps();
            });
        }

        // 5. Investment Projects under Sectors
        if (!Schema::hasTable('investment_projects')) {
            Schema::create('investment_projects', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sector_id')->nullable();
                $table->string('project_name');
                $table->string('project_id_code')->unique();
                $table->decimal('target_budget', 16, 2)->default(0);
                $table->decimal('min_investment', 14, 2)->default(50000);
                $table->decimal('max_investment', 16, 2)->default(10000000);
                $table->decimal('total_raised', 16, 2)->default(0);
                $table->integer('investors_count')->default(0);
                $table->string('duration')->default('নির্ধারিত');
                $table->integer('progress_percentage')->default(0);
                $table->text('description')->nullable();
                $table->text('profit_policy')->nullable();
                $table->longText('progress_report')->nullable();
                $table->string('featured_image')->nullable();
                $table->json('gallery_images')->nullable();
                $table->json('documents')->nullable();
                $table->string('status')->default('open'); // open, running, completed, closed
                $table->timestamps();
            });
        }

        // 6. User Investments
        if (!Schema::hasTable('user_investments')) {
            Schema::create('user_investments', function (Blueprint $table) {
                $table->id();
                $table->string('investment_code')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('project_id')->nullable();
                $table->unsignedBigInteger('sector_id')->nullable();
                $table->decimal('amount', 16, 2);
                $table->decimal('return_earned', 16, 2)->default(0);
                $table->decimal('paid_dividend', 16, 2)->default(0);
                $table->decimal('pending_dividend', 16, 2)->default(0);
                $table->date('investment_date');
                $table->date('maturity_date')->nullable();
                $table->string('payment_method')->default('wallet'); // wallet, bkash, nagad, rocket, bank_transfer
                $table->string('transaction_id')->nullable();
                $table->string('payment_proof')->nullable();
                $table->string('status')->default('pending'); // pending, active, completed, rejected, cancelled
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // 7. Investment Dividends & Profit Management
        if (!Schema::hasTable('investment_dividends')) {
            Schema::create('investment_dividends', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_investment_id');
                $table->unsignedBigInteger('user_id');
                $table->decimal('amount', 14, 2);
                $table->date('declaration_date');
                $table->date('payment_date')->nullable();
                $table->string('status')->default('payable'); // payable, paid
                $table->string('payout_method')->nullable();
                $table->string('transaction_reference')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 8. Investment & Capital Fund Withdrawals
        if (!Schema::hasTable('investment_withdrawals')) {
            Schema::create('investment_withdrawals', function (Blueprint $table) {
                $table->id();
                $table->string('withdrawal_code')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('user_investment_id')->nullable();
                $table->decimal('amount', 14, 2);
                $table->string('withdrawal_type')->default('profit'); // profit, capital, dividend
                $table->string('payment_method')->default('bkash');
                $table->string('account_number');
                $table->text('reason')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected, processing
                $table->text('admin_remarks')->nullable();
                $table->string('transaction_reference')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }

        // 9. Share Structure & Settings
        if (!Schema::hasTable('share_settings')) {
            Schema::create('share_settings', function (Blueprint $table) {
                $table->id();
                $table->integer('total_shares')->default(100);
                $table->decimal('price_per_share', 12, 2)->default(5000.00);
                $table->decimal('total_share_capital', 16, 2)->default(500000.00);
                $table->decimal('office_expense_per_share', 12, 2)->default(3000.00);
                $table->integer('allocated_shares')->default(0);
                $table->decimal('registration_fee', 12, 2)->default(2000.00);
                $table->timestamps();
            });
        }

        // 10. Shareholder Profiles
        if (!Schema::hasTable('shareholder_profiles')) {
            Schema::create('shareholder_profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('shareholder_code')->unique(); // e.g. SH-100234
                $table->integer('shares_count')->default(1);
                $table->decimal('total_investment', 16, 2)->default(5000.00);
                $table->decimal('office_expense_paid', 14, 2)->default(3000.00);
                $table->decimal('registration_fee_paid', 12, 2)->default(2000.00);
                $table->decimal('total_dividend_earned', 16, 2)->default(0);
                $table->decimal('total_dividend_withdrawn', 16, 2)->default(0);
                $table->decimal('available_dividend_balance', 16, 2)->default(0);
                $table->string('nominee_name')->nullable();
                $table->string('nominee_phone')->nullable();
                $table->string('nominee_relation')->nullable();
                $table->string('nominee_nid')->nullable();
                $table->string('nid_number')->nullable();
                $table->string('status')->default('active'); // active, pending_approval, suspended
                $table->timestamps();
            });
        }

        // 11. Share Transactions (Allocation, Purchase, Office Fee, Dividend Payout)
        if (!Schema::hasTable('share_transactions')) {
            Schema::create('share_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('shareholder_id');
                $table->unsignedBigInteger('user_id');
                $table->string('transaction_type'); // purchase, office_expense, dividend_declared, dividend_payout, share_transfer
                $table->integer('shares_count')->default(0);
                $table->decimal('amount', 14, 2);
                $table->decimal('office_expense', 14, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->string('transaction_reference')->nullable();
                $table->string('status')->default('completed'); // completed, pending, rejected
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 12. Partnerships
        if (!Schema::hasTable('partnerships')) {
            Schema::create('partnerships', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('partner_code')->unique(); // e.g. PRT-100456
                $table->string('partner_name');
                $table->string('business_name')->nullable();
                $table->string('partnership_type')->default('business_expansion'); // business_expansion, equity, project_joint_venture
                $table->string('contract_number')->nullable();
                $table->string('agreement_file')->nullable();
                $table->decimal('capital_contribution', 16, 2)->default(0);
                $table->decimal('profit_share_percentage', 5, 2)->default(0);
                $table->decimal('total_profit_shared', 16, 2)->default(0);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('status')->default('active'); // active, pending, expired, terminated
                $table->text('terms_and_conditions')->nullable();
                $table->timestamps();
            });
        }

        // 13. Notice Board
        if (!Schema::hasTable('notices')) {
            Schema::create('notices', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('notice_type')->default('general'); // general, urgent, announcement, project_update
                $table->longText('content');
                $table->string('attachment_file')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->boolean('is_active')->default(true);
                $table->date('publish_date')->nullable();
                $table->date('expiry_date')->nullable();
                $table->timestamps();
            });
        }

        // 14. Interactive Community Posts & Forum
        if (!Schema::hasTable('community_posts')) {
            Schema::create('community_posts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('title');
                $table->string('category')->default('general'); // general, investment_discussion, project_update, suggestion
                $table->longText('content');
                $table->string('image')->nullable();
                $table->integer('likes_count')->default(0);
                $table->integer('comments_count')->default(0);
                $table->boolean('is_pinned')->default(false);
                $table->string('status')->default('approved'); // approved, pending, hidden
                $table->timestamps();
            });
        }

        // 15. Community Comments
        if (!Schema::hasTable('community_comments')) {
            Schema::create('community_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('user_id');
                $table->text('comment');
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
            });
        }

        // 16. Grievance & Feedback System (অভিযোগ ও পরামর্শ জমা দেওয়ার ব্যবস্থা)
        if (!Schema::hasTable('grievances')) {
            Schema::create('grievances', function (Blueprint $table) {
                $table->id();
                $table->string('tracking_code')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->string('type')->default('complaint'); // complaint, suggestion, feedback, inquiry
                $table->string('category')->default('service'); // service, payment, investment, staff, general
                $table->string('subject');
                $table->longText('description');
                $table->string('attachment')->nullable();
                $table->string('status')->default('pending'); // pending, investigating, resolved, rejected
                $table->longText('admin_response')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // 17. Central Financial Ledger (সমন্বিত সেন্ট্রাল ফাইন্যান্সিয়াল ম্যানেজমেন্ট)
        if (!Schema::hasTable('central_finances')) {
            Schema::create('central_finances', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_code')->unique();
                $table->string('entry_type'); // income, expense
                $table->string('category'); // product_sales, service, membership_fee, investment_collection, share_capital, project_expense, employee_salary, office_admin_expense, customer_withdrawal, profit_payout, other
                $table->decimal('amount', 16, 2);
                $table->date('entry_date');
                $table->string('payment_method')->default('cash');
                $table->string('reference_type')->nullable(); // user_investment, order, payroll, share, etc.
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 18. HRM Provident Fund (P/F) Management
        if (!Schema::hasTable('hrm_provident_funds')) {
            Schema::create('hrm_provident_funds', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('month_year'); // e.g. 2026-11
                $table->decimal('employee_contribution', 12, 2)->default(0);
                $table->decimal('company_contribution', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->string('status')->default('credited'); // credited, withdrawn, adjusted
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 19. Agent Inventory & Product Balance Management
        if (!Schema::hasTable('agent_inventory_balances')) {
            Schema::create('agent_inventory_balances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_id');
                $table->unsignedBigInteger('stock_id')->nullable();
                $table->string('product_name');
                $table->decimal('product_price', 12, 2)->default(0);
                $table->integer('stock_quantity')->default(0);
                $table->decimal('payment_balance', 14, 2)->default(0);
                $table->decimal('customer_balance', 14, 2)->default(0);
                $table->decimal('total_sold_amount', 16, 2)->default(0);
                $table->decimal('commission_earned', 14, 2)->default(0);
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }

        // 20. Extend Users Table for Customer ID, Nominee, Addresses, Agent Profile & Card Type
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'customer_id')) {
                $table->string('customer_id', 50)->nullable()->unique()->after('username');
            }
            if (!Schema::hasColumn('users', 'nid_number')) {
                $table->string('nid_number', 50)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'nid_document')) {
                $table->string('nid_document')->nullable()->after('nid_number');
            }
            if (!Schema::hasColumn('users', 'present_address')) {
                $table->text('present_address')->nullable()->after('nid_document');
            }
            if (!Schema::hasColumn('users', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('present_address');
            }
            if (!Schema::hasColumn('users', 'nominee_name')) {
                $table->string('nominee_name')->nullable()->after('permanent_address');
            }
            if (!Schema::hasColumn('users', 'nominee_phone')) {
                $table->string('nominee_phone')->nullable()->after('nominee_name');
            }
            if (!Schema::hasColumn('users', 'nominee_relation')) {
                $table->string('nominee_relation')->nullable()->after('nominee_phone');
            }
            if (!Schema::hasColumn('users', 'agent_shop_name')) {
                $table->string('agent_shop_name')->nullable()->after('nominee_relation');
            }
            if (!Schema::hasColumn('users', 'agent_type')) {
                $table->string('agent_type')->nullable()->after('agent_shop_name'); // general_shop, own_project_sales
            }
            if (!Schema::hasColumn('users', 'agent_area')) {
                $table->string('agent_area')->nullable()->after('agent_type');
            }
            if (!Schema::hasColumn('users', 'dealer_id')) {
                $table->string('dealer_id', 50)->nullable()->after('agent_area');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'customer_id',
                'nid_number',
                'nid_document',
                'present_address',
                'permanent_address',
                'nominee_name',
                'nominee_phone',
                'nominee_relation',
                'agent_shop_name',
                'agent_type',
                'agent_area',
                'dealer_id',
            ]);
        });

        Schema::dropIfExists('agent_inventory_balances');
        Schema::dropIfExists('hrm_provident_funds');
        Schema::dropIfExists('central_finances');
        Schema::dropIfExists('grievances');
        Schema::dropIfExists('community_comments');
        Schema::dropIfExists('community_posts');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('partnerships');
        Schema::dropIfExists('share_transactions');
        Schema::dropIfExists('shareholder_profiles');
        Schema::dropIfExists('share_settings');
        Schema::dropIfExists('investment_withdrawals');
        Schema::dropIfExists('investment_dividends');
        Schema::dropIfExists('user_investments');
        Schema::dropIfExists('investment_projects');
        Schema::dropIfExists('investment_sectors');
        Schema::dropIfExists('corporate_activities');
        Schema::dropIfExists('corporate_profiles');
        Schema::dropIfExists('board_members');
    }
};

