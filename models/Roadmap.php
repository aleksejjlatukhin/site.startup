<?php


namespace app\models;

/**
 * Дорожная карта по сегменту проекта
 *
 * Class Roadmap
 * @package app\models
 */
class Roadmap extends PropertyContainer
{

    /**
     * Roadmap constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $segment = Segments::findOne($id);
        $confirm_segment = Segments::findOne(['id' => $id, 'exist_confirm' =>  1]);

        $last_gps = Problems::find()->where(['basic_confirm_id' => $segment->confirm->id])->orderBy(['created_at' => SORT_DESC])->one();
        $first_confirm_gps = Problems::find()->where(['basic_confirm_id' => $segment->confirm->id, 'exist_confirm' => 1])->andWhere(['not', ['time_confirm' => null]])->orderBy(['time_confirm' => SORT_ASC])->one();

        $last_gcp = Gcps::find()->where(['segment_id' => $segment->id])->orderBy(['created_at' => SORT_DESC])->one();
        $first_confirm_gcp = Gcps::find()->where(['segment_id' => $segment->id, 'exist_confirm' => 1])->andWhere(['not', ['time_confirm' => null]])->orderBy(['time_confirm' => SORT_ASC])->one();

        $last_mvp = Mvps::find()->where(['segment_id' => $segment->id])->orderBy(['created_at' => SORT_DESC])->one();
        $first_confirm_mvp = Mvps::find()->where(['segment_id' => $segment->id, 'exist_confirm' => 1])->andWhere(['not', ['time_confirm' => null]])->orderBy(['time_confirm' => SORT_ASC])->one();

        $this->addProperty('created_at', $segment->created_at);
        $this->addProperty('plan_segment_confirm', ($segment->created_at + 3600*24*10));
        $this->addProperty('plan_gps', ($segment->created_at + 3600*24*20));
        $this->addProperty('plan_gps_confirm', ($segment->created_at + 3600*24*30));
        $this->addProperty('plan_gcp', ($segment->created_at + 3600*24*40));
        $this->addProperty('plan_gcp_confirm', ($segment->created_at + 3600*24*50));
        $this->addProperty('plan_mvp', ($segment->created_at + 3600*24*60));
        $this->addProperty('plan_mvp_confirm', ($segment->created_at + 3600*24*70));

        $this->addProperty('fact_segment_confirm', $confirm_segment->time_confirm);
        $this->addProperty('fact_gps', $last_gps->created_at);
        $this->addProperty('fact_gps_confirm', $first_confirm_gps->time_confirm);
        $this->addProperty('fact_gcp', $last_gcp->created_at);
        $this->addProperty('fact_gcp_confirm', $first_confirm_gcp->time_confirm);
        $this->addProperty('fact_mvp', $last_mvp->created_at);
        $this->addProperty('fact_mvp_confirm', $first_confirm_mvp->time_confirm);

        $this->addProperty('id_confirm_segment', $segment->confirm->id);
        $this->addProperty('id_page_last_problem', $last_gps->confirmSegmentId);
        $this->addProperty('id_confirm_problem', $first_confirm_gps->confirm->id);
        $this->addProperty('id_page_last_gcp', $last_gcp->confirmProblemId);
        $this->addProperty('id_confirm_gcp', $first_confirm_gcp->confirm->id);
        $this->addProperty('id_page_last_mvp', $last_mvp->confirmGcpId);
        $this->addProperty('id_confirm_mvp', $first_confirm_mvp->confirm->id);
        $this->addProperty('segment_name', $segment->name);

        return $this;
    }

}